<?php

function hatchers_shared_secret()
{
    return trim((string) get_env_setting('HATCHERS_SHARED_SECRET', get_env_setting('WEBSITE_PLATFORM_SHARED_SECRET', '')));
}

function hatchers_json_response($status, array $payload)
{
    http_response_code((int) $status);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function hatchers_request_header($name)
{
    $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return isset($_SERVER[$key]) ? trim((string) $_SERVER[$key]) : '';
}

function hatchers_verify_signed_body($rawBody)
{
    $sharedSecret = hatchers_shared_secret();
    if ($sharedSecret === '') {
        hatchers_json_response(500, ['success' => false, 'error' => 'WEBSITE_PLATFORM_SHARED_SECRET is not configured.']);
    }

    $signature = hatchers_request_header('X-Hatchers-Signature');
    $expected = hash_hmac('sha256', (string) $rawBody, $sharedSecret);
    if ($signature === '' || !hash_equals($expected, $signature)) {
        hatchers_json_response(403, ['success' => false, 'error' => 'Invalid sync signature.']);
    }
}

function hatchers_find_user(array $identifiers)
{
    global $config;

    $pairs = [];
    foreach (['username', 'previous_username', 'email', 'previous_email'] as $field) {
        $value = trim((string) ($identifiers[$field] ?? ''));
        if ($value !== '') {
            $pairs[] = [
                in_array($field, ['email', 'previous_email'], true) ? 'email' : 'username',
                $value,
            ];
        }
    }

    foreach ($pairs as $pair) {
        $user = ORM::for_table($config['db']['pre'] . 'user')
            ->where($pair[0], $pair[1])
            ->find_one();
        if (!empty($user)) {
            return $user;
        }
    }

    return null;
}

function hatchers_find_or_create_user(array $payload)
{
    global $config;

    $username = trim((string) ($payload['username'] ?? ''));
    $email = trim((string) ($payload['email'] ?? ''));
    $name = trim((string) ($payload['name'] ?? ''));
    $password = (string) ($payload['password'] ?? '');

    if ($username === '') {
        hatchers_json_response(422, ['success' => false, 'error' => 'Username is required.']);
    }

    $user = hatchers_find_user($payload);
    $isNew = empty($user);

    $existingUsername = ORM::for_table($config['db']['pre'] . 'user')
        ->where('username', $username)
        ->find_one();
    if (!empty($existingUsername) && ($isNew || (int) $existingUsername['id'] !== (int) $user['id'])) {
        hatchers_json_response(422, ['success' => false, 'error' => 'Username already exists in Atlas.']);
    }

    if ($email !== '') {
        $existingEmail = ORM::for_table($config['db']['pre'] . 'user')
            ->where('email', $email)
            ->find_one();
        if (!empty($existingEmail) && ($isNew || (int) $existingEmail['id'] !== (int) $user['id'])) {
            hatchers_json_response(422, ['success' => false, 'error' => 'Email already exists in Atlas.']);
        }
    }

    $now = date('Y-m-d H:i:s');
    if ($isNew) {
        $location = getLocationInfoByIp();
        $user = ORM::for_table($config['db']['pre'] . 'user')->create();
        $user->status = '1';
        $user->group_id = get_option('default_user_plan');
        $user->created_at = $now;
        $user->country = isset($location['country']) ? $location['country'] : '';
        $user->country_code = isset($location['countryCode']) ? $location['countryCode'] : '';
        $user->referral_key = uniqid(get_random_string(5));
        $user->user_type = 'user';
    }

    $user->name = $name !== '' ? $name : $username;
    $user->username = $username;
    $user->email = $email !== '' ? $email : null;
    $user->updated_at = $now;

    if ($password !== '') {
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
    }

    $user->save();

    return [
        'user' => $user,
        'created' => $isNew,
    ];
}

function hatchers_decode_json_option($userId, $option, array $default = [])
{
    $raw = get_user_option($userId, $option, '');
    if ($raw === '') {
        return $default;
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $default;
}

function hatchers_compact_array(array $data)
{
    $clean = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $value = hatchers_compact_array($value);
        }

        if ($value === null || $value === '' || $value === [] || $value === false) {
            continue;
        }

        $clean[$key] = $value;
    }

    return $clean;
}

function hatchers_normalize_list($value)
{
    if (is_array($value)) {
        $items = $value;
    } else {
        $items = preg_split('/[\r\n,]+/', (string) $value);
    }

    $items = array_map('trim', $items);
    return array_values(array_filter($items, function ($item) {
        return $item !== '';
    }));
}

function hatchers_get_tool_knowledge()
{
    return [
        'lms' => [
            'name' => 'LMS',
            'url' => 'lms.hatchers.ai',
            'purpose' => 'Founder onboarding, mentor assignment, milestones, tasks, learning, and mentor coordination.',
            'capabilities' => [
                'Founders meet their mentor and follow weekly execution plans.',
                'Mentors can review milestones, learning progress, meetings, and tasks.',
                'Atlas in LMS should help founders and mentors understand next steps and founder status.',
            ],
        ],
        'atlas' => [
            'name' => 'Atlas',
            'url' => 'atlas.hatchers.ai',
            'purpose' => 'AI growth workspace for marketing, campaigns, content generation, and specialized agents.',
            'capabilities' => [
                'Generate social media content, campaign ideas, and business messaging.',
                'Store company intelligence used across Hatchers tools.',
                'Direct founders here when they need content creation, campaign support, or specialist agents.',
            ],
        ],
        'bazaar' => [
            'name' => 'Bazaar',
            'url' => 'bazaar.hatchers.ai',
            'purpose' => 'Ecommerce website builder for founders selling products online.',
            'capabilities' => [
                'Manage products, categories, extras, orders, coupons, taxes, shipping, and reports.',
                'Customize store basics including theme, SEO, banners, blogs, FAQs, testimonials, and CMS pages.',
                'Accept payments using enabled payment methods configured by the platform and vendor settings.',
                'Vendors typically work from Dashboard, Products, Orders, Subscription Plans, Payment Methods, Working Hours, and Basic Settings.',
            ],
        ],
        'servio' => [
            'name' => 'Servio',
            'url' => 'servio.hatchers.ai',
            'purpose' => 'Service business website builder with bookings and optional products.',
            'capabilities' => [
                'Manage services, categories, bookings, staff, working hours, payments, and reports.',
                'Customize website basics including theme, SEO, blogs, FAQs, testimonials, banners, and landing settings.',
                'Handle bookings, booking status, invoices, and service presentation.',
                'Guide founders on setup steps such as creating services, setting hours, publishing pages, and receiving bookings.',
            ],
        ],
    ];
}

function hatchers_system_knowledge_paths()
{
    $paths = [];

    $configured = trim((string) get_env_setting('HATCHERS_SYSTEM_KNOWLEDGE_PATH', ''));
    if ($configured !== '') {
        $paths[] = $configured;
    }

    $atlasRoot = dirname(__DIR__, 2);
    $workspaceRoot = dirname($atlasRoot);

    $paths[] = $workspaceRoot . '/app.hatchers.ai/docs/HATCHERS_SYSTEM_KNOWLEDGE.md';
    $paths[] = $atlasRoot . '/includes/data/HATCHERS_SYSTEM_KNOWLEDGE.md';

    return array_values(array_unique($paths));
}

function hatchers_read_system_knowledge()
{
    foreach (hatchers_system_knowledge_paths() as $path) {
        if (!is_string($path) || $path === '' || !is_file($path) || !is_readable($path)) {
            continue;
        }

        $contents = file_get_contents($path);
        if (is_string($contents) && trim($contents) !== '') {
            return trim($contents);
        }
    }

    return '';
}

function hatchers_system_knowledge_text()
{
    $knowledge = hatchers_read_system_knowledge();
    if ($knowledge !== '') {
        return $knowledge;
    }

    return trim(
        "Hatchers OS is the founder home base and subscription authority.\n" .
        "Atlas handles AI, campaigns, agents, and company intelligence.\n" .
        "LMS handles learning plans, milestones, tasks, and mentor execution.\n" .
        "Bazaar handles products, storefronts, and orders.\n" .
        "Servio handles services, bookings, staff, and working hours.\n" .
        "Founders should not be told to buy separate plans inside those tools."
    );
}

function hatchers_get_founder_intelligence($userId)
{
    $intelligence = hatchers_decode_json_option($userId, 'hatchers_founder_intelligence', [
        'founder' => [],
        'sources' => [],
        'company' => [],
        'operations' => [],
        'mentor' => [],
        'history' => [],
        'updated_at' => '',
    ]);

    $profile = function_exists('social_media_get_profile') ? social_media_get_profile($userId) : [];
    $companyIntelligence = function_exists('social_media_get_company_intelligence')
        ? social_media_get_company_intelligence($userId)
        : [];

    $intelligence['company'] = hatchers_compact_array(array_merge(
        isset($intelligence['company']) && is_array($intelligence['company']) ? $intelligence['company'] : [],
        is_array($profile) ? $profile : [],
        is_array($companyIntelligence) ? ['company_intelligence' => $companyIntelligence] : []
    ));

    $intelligence['founder'] = hatchers_compact_array(array_merge(
        isset($intelligence['founder']) && is_array($intelligence['founder']) ? $intelligence['founder'] : [],
        [
            'company_brief' => trim((string) get_user_option($userId, 'hatchers_company_brief', '')),
            'phone' => trim((string) get_user_option($userId, 'hatchers_phone', '')),
            'last_source' => trim((string) get_user_option($userId, 'hatchers_source', '')),
        ]
    ));

    return $intelligence;
}

function hatchers_update_founder_intelligence($userId, array $payload)
{
    $now = date('Y-m-d H:i:s');
    $app = trim((string) ($payload['app'] ?? 'atlas'));
    $currentPage = trim((string) ($payload['current_page'] ?? ''));
    $sourceSnapshot = isset($payload['snapshot']) && is_array($payload['snapshot']) ? $payload['snapshot'] : [];

    $intelligence = hatchers_get_founder_intelligence($userId);
    $intelligence['updated_at'] = $now;

    $intelligence['founder'] = hatchers_compact_array(array_merge(
        isset($intelligence['founder']) && is_array($intelligence['founder']) ? $intelligence['founder'] : [],
        [
            'name' => trim((string) ($payload['name'] ?? '')),
            'username' => trim((string) ($payload['username'] ?? '')),
            'email' => trim((string) ($payload['email'] ?? '')),
            'role' => trim((string) ($payload['role'] ?? 'founder')),
            'company_brief' => trim((string) ($payload['company_brief'] ?? '')),
            'phone' => trim((string) ($payload['phone'] ?? '')),
        ]
    ));

    if ($app !== '') {
        $intelligence['sources'][$app] = [
            'updated_at' => $now,
            'current_page' => $currentPage,
            'snapshot' => hatchers_compact_array($sourceSnapshot),
        ];
    }

    if (!empty($payload['mentor']) && is_array($payload['mentor'])) {
        $intelligence['mentor'] = hatchers_compact_array(array_merge(
            isset($intelligence['mentor']) && is_array($intelligence['mentor']) ? $intelligence['mentor'] : [],
            $payload['mentor']
        ));
    }

    if (!empty($payload['company']) && is_array($payload['company'])) {
        $companyData = hatchers_compact_array($payload['company']);
        $intelligence['company'] = hatchers_compact_array(array_merge(
            isset($intelligence['company']) && is_array($intelligence['company']) ? $intelligence['company'] : [],
            $companyData
        ));
        if (function_exists('social_media_save_profile')) {
            social_media_save_profile($userId, $companyData);
        }
    }

    if (!empty($payload['operations']) && is_array($payload['operations'])) {
        $intelligence['operations'] = hatchers_compact_array(array_merge(
            isset($intelligence['operations']) && is_array($intelligence['operations']) ? $intelligence['operations'] : [],
            $payload['operations']
        ));
    }

    $history = isset($intelligence['history']) && is_array($intelligence['history']) ? $intelligence['history'] : [];
    $history[] = hatchers_compact_array([
        'app' => $app,
        'current_page' => $currentPage,
        'updated_at' => $now,
        'summary' => trim((string) ($payload['sync_summary'] ?? '')),
    ]);
    $intelligence['history'] = array_slice($history, -20);

    update_user_option($userId, 'hatchers_founder_intelligence', json_encode($intelligence));
    update_user_option($userId, 'hatchers_source', $app);

    if (!empty($intelligence['founder']['company_brief'])) {
        update_user_option($userId, 'hatchers_company_brief', $intelligence['founder']['company_brief']);
    }
    if (!empty($intelligence['founder']['phone'])) {
        update_user_option($userId, 'hatchers_phone', $intelligence['founder']['phone']);
    }

    return $intelligence;
}

function hatchers_get_assistant_history($userId)
{
    return hatchers_decode_json_option($userId, 'hatchers_assistant_history', []);
}

function hatchers_record_assistant_exchange($userId, $app, $message, $reply)
{
    $history = hatchers_get_assistant_history($userId);
    $history[] = [
        'app' => trim((string) $app),
        'user' => trim((string) $message),
        'assistant' => trim((string) $reply),
        'created_at' => date('Y-m-d H:i:s'),
    ];
    $history = array_slice($history, -20);
    update_user_option($userId, 'hatchers_assistant_history', json_encode($history));
    return $history;
}

function hatchers_tool_knowledge_text($currentApp = '')
{
    $knowledge = hatchers_get_tool_knowledge();
    $lines = [];

    foreach ($knowledge as $slug => $tool) {
        $prefix = $slug === $currentApp ? '[current app] ' : '';
        $lines[] = $prefix . strtoupper($slug) . ': ' . $tool['purpose'];
        foreach ($tool['capabilities'] as $capability) {
            $lines[] = '- ' . $capability;
        }
    }

    return implode("\n", $lines);
}

function hatchers_build_assistant_instructions($userId, array $payload)
{
    $currentApp = trim((string) ($payload['app'] ?? ''));
    $currentPage = trim((string) ($payload['current_page'] ?? ''));
    $intelligence = hatchers_get_founder_intelligence($userId);
    $mentorBrief = isset($payload['mentor_brief']) && is_array($payload['mentor_brief']) ? $payload['mentor_brief'] : [];
    $actionPlan = hatchers_get_founder_action_plan_text($userId, 5);
    $assistantMode = trim((string) ($payload['assistant_mode'] ?? ''));

    return trim(
        "You are Atlas, the Hatchers OS founder mentor and operating assistant.\n\n" .
        "Your job:\n" .
        "- Help founders move forward inside Hatchers OS workspaces such as Learning Hub, Website Studio, Commerce, Marketing, AI Studio, Tasks, First 100, Media Library, Analytics, and Settings.\n" .
        "- Answer from the founder's real operating context when it is available.\n" .
        "- Act like a calm but commercially sharp founder mentor.\n" .
        "- Use direct-response thinking in paraphrased form: sharpen the offer, improve the hook, strengthen urgency, reduce friction, use risk reversal when relevant, and keep follow-up alive.\n" .
        "- Prioritize the next revenue move over generic inspiration.\n" .
        "- When a mentor asks about a founder, summarize progress, blockers, tasks, and next steps clearly.\n" .
        "- If information is missing, say so and ask the smallest useful follow-up question.\n" .
        "- Never invent UI paths or features that are not supported by the tool knowledge and current snapshot.\n" .
        "- Keep the founder inside Hatchers OS. Do not send them out to separate product brands or external Hatchers tool URLs in normal guidance.\n" .
        "- Never perform or claim a write action unless the user explicitly confirmed it.\n" .
        "- Before a write action, ask whether the user is acting as the founder or mentor when that is not already clear.\n" .
        "- Keep answers concise, supportive, operational, and founder-facing.\n" .
        "- When appropriate, end with the next 1 to 3 concrete actions.\n\n" .
        "Default response style:\n" .
        "- Do not answer with one long wall of text.\n" .
        "- Prefer short sections with plain headings such as Situation:, What matters most:, Next actions:, or Fix this first: when helpful.\n" .
        "- Use short bullets for lists and numbered steps for sequences.\n" .
        "- Keep most replies to 2 to 5 short paragraphs or 3 to 5 bullets unless the founder asks for depth.\n" .
        "- If the founder is confused, start with the direct answer first, then explain briefly.\n" .
        "- If the founder asks what to do next, give the answer as explicit actions, not theory.\n" .
        "- If reviewing an offer, campaign, or page, structure it as Strengths:, Weaknesses:, and Next move:.\n" .
        "- If diagnosing a blocker, structure it as Likely issue:, Why it matters:, and Next actions:.\n" .
        "- If the founder seems overwhelmed, reduce the response to the single best next move plus up to two supporting actions.\n" .
        "- Do not use markdown tables.\n\n" .
        "Assistant mode: " . ($assistantMode !== '' ? $assistantMode : 'default') . "\n" .
        "Current app: " . ($currentApp !== '' ? $currentApp : 'unknown') . "\n" .
        "Current page: " . ($currentPage !== '' ? $currentPage : 'unknown') . "\n\n" .
        (!empty($mentorBrief) ? "Mentor brief JSON:\n" . json_encode($mentorBrief) . "\n\n" : '') .
        "Central system knowledge:\n" . hatchers_system_knowledge_text() . "\n\n" .
        "Tool knowledge:\n" . hatchers_tool_knowledge_text($currentApp) . "\n\n" .
        ($actionPlan !== '' ? "Current founder action plan:\n" . $actionPlan . "\n\n" : '') .
        "Founder intelligence JSON:\n" . json_encode($intelligence)
    );
}

function hatchers_extract_response_text(array $data)
{
    if (!empty($data['output_text']) && is_string($data['output_text'])) {
        return trim($data['output_text']);
    }

    if (empty($data['output']) || !is_array($data['output'])) {
        return '';
    }

    $parts = [];
    foreach ($data['output'] as $item) {
        if (empty($item['content']) || !is_array($item['content'])) {
            continue;
        }

        foreach ($item['content'] as $content) {
            if (!empty($content['text']) && is_string($content['text'])) {
                $parts[] = $content['text'];
            }
        }
    }

    return trim(implode("\n", $parts));
}

function hatchers_call_openai_responses(array $messages, $instructions)
{
    $apiKey = trim((string) get_api_key());
    if ($apiKey === '') {
        return ['ok' => false, 'error' => 'OpenAI API key is not configured in Atlas.'];
    }

    $model = normalize_openai_model(get_default_openai_chat_model(), 'text');
    $payload = [
        'model' => $model,
        'input' => $messages,
        'instructions' => (string) $instructions,
        'max_output_tokens' => 700,
        'temperature' => 0.4,
    ];

    $ch = curl_init('https://api.openai.com/v1/responses');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 45,
    ]);

    $responseBody = curl_exec($ch);
    $curlError = curl_error($ch);
    $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($responseBody === false || $curlError) {
        return ['ok' => false, 'error' => $curlError !== '' ? $curlError : 'OpenAI request failed.'];
    }

    $decoded = json_decode($responseBody, true);
    if ($statusCode >= 400) {
        $message = isset($decoded['error']['message']) ? $decoded['error']['message'] : 'OpenAI request failed.';
        return ['ok' => false, 'error' => $message];
    }

    if (!is_array($decoded)) {
        return ['ok' => false, 'error' => 'Invalid OpenAI response.'];
    }

    return ['ok' => true, 'data' => $decoded];
}

function hatchers_get_founder_intelligence_text($userId, array $options = [])
{
    $intelligence = hatchers_get_founder_intelligence($userId);
    $lines = [];

    if (!empty($intelligence['founder']['name'])) {
        $lines[] = 'Founder: ' . $intelligence['founder']['name'];
    }
    if (!empty($intelligence['founder']['username'])) {
        $lines[] = 'Username: ' . $intelligence['founder']['username'];
    }
    if (!empty($intelligence['founder']['company_brief'])) {
        $lines[] = 'Founder brief: ' . $intelligence['founder']['company_brief'];
    }

    if (!empty($intelligence['mentor']['name'])) {
        $lines[] = 'Assigned mentor: ' . $intelligence['mentor']['name'];
    }

    if (!empty($intelligence['company']['company_name'])) {
        $lines[] = 'Company: ' . $intelligence['company']['company_name'];
    }
    if (!empty($intelligence['company']['company_description'])) {
        $lines[] = 'Company description: ' . $intelligence['company']['company_description'];
    }
    if (!empty($intelligence['company']['target_audience'])) {
        $lines[] = 'Target audience: ' . $intelligence['company']['target_audience'];
    }
    if (!empty($intelligence['company']['ideal_customer_profile'])) {
        $lines[] = 'Ideal customer profile: ' . $intelligence['company']['ideal_customer_profile'];
    }
    if (!empty($intelligence['company']['differentiators'])) {
        $lines[] = 'Differentiators: ' . $intelligence['company']['differentiators'];
    }
    if (!empty($intelligence['company']['content_goals'])) {
        $lines[] = 'Content goals: ' . $intelligence['company']['content_goals'];
    }
    if (!empty($intelligence['company']['key_products'])) {
        $lines[] = 'Key products or services: ' . $intelligence['company']['key_products'];
    }
    if (!empty($intelligence['company']['brand_voice'])) {
        $lines[] = 'Brand voice: ' . $intelligence['company']['brand_voice'];
    }
    if (!empty($intelligence['company']['tone_attributes']) && is_array($intelligence['company']['tone_attributes'])) {
        $lines[] = 'Tone attributes: ' . implode(', ', array_slice($intelligence['company']['tone_attributes'], 0, 8));
    }

    if (!empty($intelligence['operations']) && is_array($intelligence['operations'])) {
        foreach ($intelligence['operations'] as $key => $value) {
            if (is_array($value)) {
                $summary = [];
                foreach ($value as $subKey => $subValue) {
                    if (is_scalar($subValue) && $subValue !== '' && $subValue !== null) {
                        $summary[] = $subKey . ': ' . $subValue;
                    }
                }
                if (!empty($summary)) {
                    $lines[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . implode('; ', array_slice($summary, 0, 6));
                }
            }
        }
    }

    if (!empty($intelligence['sources']) && is_array($intelligence['sources'])) {
        foreach ($intelligence['sources'] as $source => $snapshot) {
            $snapshotParts = [];
            if (!empty($snapshot['current_page'])) {
                $snapshotParts[] = 'page=' . $snapshot['current_page'];
            }
            if (!empty($snapshot['snapshot']) && is_array($snapshot['snapshot'])) {
                foreach ($snapshot['snapshot'] as $snapKey => $snapValue) {
                    if (is_scalar($snapValue) && $snapValue !== '' && $snapValue !== null) {
                        $snapshotParts[] = $snapKey . '=' . $snapValue;
                    }
                }
            }
            if (!empty($snapshotParts)) {
                $lines[] = strtoupper($source) . ' snapshot: ' . implode('; ', array_slice($snapshotParts, 0, 8));
            }
        }
    }

    if (!empty($intelligence['updated_at'])) {
        $lines[] = 'Intelligence updated at: ' . $intelligence['updated_at'];
    }

    return implode("\n", $lines);
}

function hatchers_get_founder_context_summary($userId)
{
    $intelligence = hatchers_get_founder_intelligence($userId);
    $founder = isset($intelligence['founder']) && is_array($intelligence['founder']) ? $intelligence['founder'] : [];
    $company = isset($intelligence['company']) && is_array($intelligence['company']) ? $intelligence['company'] : [];
    $operations = isset($intelligence['operations']) && is_array($intelligence['operations']) ? $intelligence['operations'] : [];

    $execution = isset($operations['execution']) && is_array($operations['execution']) ? $operations['execution'] : [];
    $commercial = isset($operations['commercial']) && is_array($operations['commercial']) ? $operations['commercial'] : [];

    $companyName = trim((string) ($company['company_name'] ?? ''));
    if ($companyName === '') {
        $companyName = trim((string) ($founder['company_brief'] ?? ''));
    }

    $businessModel = trim((string) ($company['business_model'] ?? ''));
    $brandVoice = trim((string) ($company['brand_voice'] ?? ''));
    $targetAudience = trim((string) ($company['target_audience'] ?? ''));
    $goal = trim((string) ($company['primary_growth_goal'] ?? ''));
    $updatedAt = trim((string) ($intelligence['updated_at'] ?? ''));
    $companyDescription = trim((string) ($company['company_description'] ?? ''));
    $idealCustomerProfile = trim((string) ($company['ideal_customer_profile'] ?? ''));
    $differentiators = trim((string) ($company['differentiators'] ?? ''));

    $summary = [
        'connected' => !empty($intelligence),
        'company_name' => $companyName,
        'business_model' => $businessModel,
        'brand_voice' => $brandVoice,
        'target_audience' => $targetAudience,
        'primary_growth_goal' => $goal,
        'open_tasks' => (int) ($execution['open_tasks'] ?? 0),
        'completed_tasks' => (int) ($execution['completed_tasks'] ?? 0),
        'orders' => (int) ($commercial['order_count'] ?? 0),
        'bookings' => (int) ($commercial['booking_count'] ?? 0),
        'revenue' => (float) ($commercial['gross_revenue'] ?? 0),
        'currency' => trim((string) ($commercial['currency'] ?? 'USD')),
        'updated_at' => $updatedAt,
        'has_company_intelligence' => $companyName !== '' || $targetAudience !== '' || $brandVoice !== '' || $goal !== '',
    ];

    $completenessSignals = [
        $companyName !== '',
        $companyDescription !== '',
        $businessModel !== '',
        $targetAudience !== '',
        $idealCustomerProfile !== '',
        $brandVoice !== '',
        $goal !== '',
        $differentiators !== '',
    ];
    $completedSignals = count(array_filter($completenessSignals));
    $summary['intelligence_completeness_score'] = (int) round(($completedSignals / max(1, count($completenessSignals))) * 100);
    $summary['needs_company_intelligence'] = $summary['intelligence_completeness_score'] < 60;
    $summary['company_intelligence_url'] = hatchers_get_platform_urls()['atlas'] . '/company-intelligence';

    $highlights = [];
    if ($companyName !== '') {
        $highlights[] = $companyName;
    }
    if ($businessModel !== '') {
        $highlights[] = $businessModel;
    }
    if ($targetAudience !== '') {
        $highlights[] = 'Audience: ' . $targetAudience;
    }
    if ($brandVoice !== '') {
        $highlights[] = 'Voice: ' . $brandVoice;
    }
    if ($goal !== '') {
        $highlights[] = 'Goal: ' . $goal;
    }

    $metrics = [];
    $metrics[] = 'Tasks ' . (int) ($execution['open_tasks'] ?? 0);
    $metrics[] = 'Orders ' . (int) ($commercial['order_count'] ?? 0);
    $metrics[] = 'Bookings ' . (int) ($commercial['booking_count'] ?? 0);
    $metrics[] = strtoupper($summary['currency']) . ' ' . number_format((float) $summary['revenue'], 0);

    $summary['highlights'] = $highlights;
    $summary['metrics'] = $metrics;

    return $summary;
}

function hatchers_get_platform_urls()
{
    return [
        'os' => rtrim((string) get_env_setting('HATCHERS_OS_URL', 'https://app.hatchers.ai'), '/'),
        'atlas' => rtrim((string) get_env_setting('ATLAS_URL', get_option('site_url')), '/'),
        'lms' => rtrim((string) get_env_setting('LMS_URL', 'https://lms.hatchers.ai'), '/'),
        'bazaar' => rtrim((string) get_env_setting('BAZAAR_URL', 'https://bazaar.hatchers.ai'), '/'),
        'servio' => rtrim((string) get_env_setting('SERVIO_URL', 'https://servio.hatchers.ai'), '/'),
    ];
}

function hatchers_resolve_website_autopilot_assets($userId)
{
    $intelligence = hatchers_get_founder_intelligence($userId);
    $sources = isset($intelligence['sources']) && is_array($intelligence['sources']) ? $intelligence['sources'] : [];
    $osSnapshot = !empty($sources['os']['snapshot']) && is_array($sources['os']['snapshot']) ? $sources['os']['snapshot'] : [];
    $websiteAutopilot = !empty($osSnapshot['website_autopilot']) && is_array($osSnapshot['website_autopilot']) ? $osSnapshot['website_autopilot'] : [];
    $assetSlots = !empty($websiteAutopilot['asset_slots']) && is_array($websiteAutopilot['asset_slots']) ? $websiteAutopilot['asset_slots'] : [];

    if (empty($assetSlots)) {
        return [];
    }

    $resolved = [];
    foreach ($assetSlots as $slot) {
        if (!is_array($slot)) {
            continue;
        }

        $query = trim((string) ($slot['query'] ?? ''));
        if ($query === '') {
            continue;
        }

        $assets = [];
        if (function_exists('social_media_search_pexels')) {
            $assets = social_media_search_pexels($query, 4);
        }

        $selected = !empty($assets[0]) && is_array($assets[0]) ? $assets[0] : [];
        $resolved[] = [
            'slot_key' => trim((string) ($slot['slot_key'] ?? '')),
            'slot_label' => trim((string) ($slot['slot_label'] ?? '')),
            'query' => $query,
            'status' => !empty($selected) ? 'selected' : 'requested',
            'provider' => !empty($selected['provider']) ? $selected['provider'] : 'pexels',
            'asset_url' => !empty($selected['url']) ? $selected['url'] : '',
            'preview_url' => !empty($selected['thumb']) ? $selected['thumb'] : (!empty($selected['url']) ? $selected['url'] : ''),
            'alt_text' => !empty($slot['alt_text']) ? $slot['alt_text'] : (!empty($selected['title']) ? $selected['title'] : ''),
            'credit_name' => !empty($selected['author_name']) ? $selected['author_name'] : '',
            'credit_url' => !empty($selected['author_url']) ? $selected['author_url'] : '',
            'asset_title' => !empty($selected['title']) ? $selected['title'] : '',
            'visual_brief' => trim((string) ($slot['visual_brief'] ?? '')),
        ];
    }

    return $resolved;
}

function hatchers_push_os_snapshot($userId, $currentPage = 'atlas', array $extra = [])
{
    $sharedSecret = hatchers_shared_secret();
    $urls = hatchers_get_platform_urls();
    if ($sharedSecret === '' || empty($urls['os'])) {
        return false;
    }

    $userId = (int) $userId;
    if ($userId <= 0) {
        return false;
    }

    $user = get_user_by_id($userId);
    if (empty($user)) {
        return false;
    }

    $intelligence = hatchers_get_founder_intelligence($userId);
    $history = hatchers_get_assistant_history($userId);
    $actions = hatchers_get_founder_action_plan($userId, 6);
    $company = isset($intelligence['company']) && is_array($intelligence['company']) ? $intelligence['company'] : [];
    $sources = isset($intelligence['sources']) && is_array($intelligence['sources']) ? $intelligence['sources'] : [];
    $operations = isset($intelligence['operations']) && is_array($intelligence['operations']) ? $intelligence['operations'] : [];

    $companyProfileComplete = !empty($company['company_name']) && !empty($company['company_description']);
    $companyIntelligenceComplete = $companyProfileComplete && (!empty($company['target_audience']) || !empty($company['ideal_customer_profile']) || !empty($company['content_goals']));
    $brandVoice = trim((string) ($company['brand_voice'] ?? ''));
    $businessModel = trim((string) ($company['business_model'] ?? ''));
    $companyName = trim((string) ($company['company_name'] ?? $user['name']));
    $primaryGrowthGoal = '';
    if (!empty($company['content_goals'])) {
        $primaryGrowthGoal = is_array($company['content_goals']) ? implode(', ', array_slice($company['content_goals'], 0, 3)) : trim((string) $company['content_goals']);
    }

    $generatedPostsCount = 0;
    $generatedCampaignsCount = 0;
    $generatedImagesCount = (int) get_user_option($userId, 'total_images_used', 0);
    $recentCampaigns = hatchers_get_recent_campaign_records($userId, 3);
    $archivedCampaigns = hatchers_get_archived_campaign_records($userId, 3);

    foreach ($history as $entry) {
        if (trim((string) ($entry['app'] ?? '')) === 'atlas') {
            $generatedPostsCount++;
        }
    }

    if (!empty($operations['campaigns']) && is_array($operations['campaigns'])) {
        if (isset($operations['campaigns']['count']) && is_numeric($operations['campaigns']['count'])) {
            $generatedCampaignsCount = max($generatedCampaignsCount, (int) $operations['campaigns']['count']);
        }

        foreach ($operations['campaigns'] as $value) {
            if (is_numeric($value)) {
                $generatedCampaignsCount = max($generatedCampaignsCount, (int) $value);
            }
        }
    }

    if (!empty($sources['atlas']['snapshot']) && is_array($sources['atlas']['snapshot'])) {
        $atlasSource = $sources['atlas']['snapshot'];
        $generatedPostsCount = max($generatedPostsCount, (int) ($atlasSource['generated_posts_count'] ?? 0));
        $generatedCampaignsCount = max($generatedCampaignsCount, (int) ($atlasSource['generated_campaigns_count'] ?? 0));
        $generatedImagesCount = max($generatedImagesCount, (int) ($atlasSource['generated_images_count'] ?? 0));
    }

    $websiteAutopilotAssets = hatchers_resolve_website_autopilot_assets($userId);
    if (!empty($websiteAutopilotAssets)) {
        $generatedImagesCount = max($generatedImagesCount, count($websiteAutopilotAssets));
    }

    $readinessScore = min(
        100,
        20
        + ($companyProfileComplete ? 25 : 0)
        + ($companyIntelligenceComplete ? 20 : 0)
        + min(15, $generatedPostsCount * 3)
        + min(10, $generatedCampaignsCount * 5)
        + min(10, count($actions) * 2)
    );

    $payload = [
        'email' => trim((string) $user['email']),
        'username' => trim((string) $user['username']),
        'updated_at' => date('c'),
        'readiness_score' => $readinessScore,
        'current_page' => $currentPage,
        'key_counts' => [
            'generated_posts_count' => $generatedPostsCount,
            'generated_campaigns_count' => $generatedCampaignsCount,
            'generated_images_count' => $generatedImagesCount,
            'recommended_actions_count' => count($actions),
        ],
        'status_flags' => [
            'company_profile_complete' => $companyProfileComplete,
            'company_intelligence_complete' => $companyIntelligenceComplete,
        ],
        'recent_activity' => [
            trim((string) ($extra['activity'] ?? 'Atlas intelligence synced.')),
            'Assistant history entries: ' . count($history) . '.',
            !empty($websiteAutopilotAssets) ? 'Website autopilot asset slots prepared in Atlas.' : '',
        ],
        'summary' => [
            'company_name' => $companyName,
            'business_model' => $businessModel,
            'brand_voice' => $brandVoice,
            'primary_growth_goal' => $primaryGrowthGoal,
            'latest_content_summary' => trim((string) ($extra['latest_content_summary'] ?? '')),
            'website_autopilot_assets_count' => count($websiteAutopilotAssets),
            'recent_campaigns' => array_map(function ($campaign) {
                return [
                    'id' => isset($campaign['id']) ? $campaign['id'] : '',
                    'title' => isset($campaign['title']) ? $campaign['title'] : '',
                    'description' => isset($campaign['description']) ? strlimiter(strip_tags((string) $campaign['description']), 120) : '',
                    'status' => isset($campaign['status']) ? $campaign['status'] : 'drafted',
                    'updated_at' => isset($campaign['updated_at']) ? $campaign['updated_at'] : '',
                    'generated_posts_count' => isset($campaign['generated_posts_count']) ? (int) $campaign['generated_posts_count'] : 0,
                    'last_generated_at' => isset($campaign['last_generated_at']) ? $campaign['last_generated_at'] : '',
                    'url' => hatchers_campaign_record_url(isset($campaign['id']) ? $campaign['id'] : ''),
                ];
            }, $recentCampaigns),
            'archived_campaigns' => array_map(function ($campaign) {
                return [
                    'id' => isset($campaign['id']) ? $campaign['id'] : '',
                    'title' => isset($campaign['title']) ? $campaign['title'] : '',
                    'description' => isset($campaign['description']) ? strlimiter(strip_tags((string) $campaign['description']), 120) : '',
                    'status' => isset($campaign['status']) ? $campaign['status'] : 'archived',
                    'updated_at' => isset($campaign['updated_at']) ? $campaign['updated_at'] : '',
                    'generated_posts_count' => isset($campaign['generated_posts_count']) ? (int) $campaign['generated_posts_count'] : 0,
                    'last_generated_at' => isset($campaign['last_generated_at']) ? $campaign['last_generated_at'] : '',
                    'url' => hatchers_campaign_record_url(isset($campaign['id']) ? $campaign['id'] : ''),
                ];
            }, $archivedCampaigns),
        ],
        'website_autopilot' => [
            'asset_slots' => $websiteAutopilotAssets,
        ],
    ];

    $body = json_encode($payload);
    if ($body === false) {
        return false;
    }

    $ch = curl_init($urls['os'] . '/integrations/snapshots/atlas');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'X-Hatchers-Signature: ' . hash_hmac('sha256', $body, $sharedSecret),
        ],
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    curl_exec($ch);
    curl_close($ch);

    return true;
}

function hatchers_get_founder_action_plan($userId, $limit = 6)
{
    $intelligence = hatchers_get_founder_intelligence($userId);
    $urls = hatchers_get_platform_urls();
    $actions = [];

    $company = isset($intelligence['company']) && is_array($intelligence['company']) ? $intelligence['company'] : [];
    $sources = isset($intelligence['sources']) && is_array($intelligence['sources']) ? $intelligence['sources'] : [];
    $atlasImagesUsed = (int) get_user_option($userId, 'total_images_used', 0);

    $hasCompanyBasics = !empty($company['company_name']) && !empty($company['company_description']);
    if (!$hasCompanyBasics) {
        $actions[] = [
            'priority' => 100,
            'platform' => 'atlas',
            'title' => 'Complete company intelligence',
            'reason' => 'Atlas needs a clear company description, audience, and offer before it can generate founder-specific strategy and content reliably.',
            'url' => $urls['atlas'] . '/company-intelligence',
            'cta' => 'Open company intelligence',
        ];
    }

    if (empty($company['target_audience']) || empty($company['ideal_customer_profile']) || empty($company['content_goals'])) {
        $actions[] = [
            'priority' => 92,
            'platform' => 'atlas',
            'title' => 'Sharpen audience and content goals',
            'reason' => 'Your ICP, target audience, and content goals are still incomplete, which weakens every marketing prompt and campaign.',
            'url' => $urls['atlas'] . '/company-intelligence',
            'cta' => 'Refine positioning',
        ];
    }

    $lmsSnapshot = !empty($sources['lms']['snapshot']) && is_array($sources['lms']['snapshot']) ? $sources['lms']['snapshot'] : [];
    if (!empty($sources['lms'])) {
        $taskCount = isset($lmsSnapshot['task_count']) ? (int) $lmsSnapshot['task_count'] : 0;
        $milestoneCount = isset($lmsSnapshot['milestone_count']) ? (int) $lmsSnapshot['milestone_count'] : 0;
        if ($taskCount === 0 || $milestoneCount === 0) {
            $actions[] = [
                'priority' => 90,
                'platform' => 'lms',
                'title' => 'Align the next execution sprint in LMS',
                'reason' => 'The founder does not yet have enough visible tasks or milestones in LMS to turn strategy into accountable weekly progress.',
                'url' => $urls['lms'] . '/mentoring',
                'cta' => 'Open LMS mentoring',
            ];
        }
    } else {
        $actions[] = [
            'priority' => 88,
            'platform' => 'lms',
            'title' => 'Start the mentor execution loop',
            'reason' => 'No LMS execution snapshot has been synced yet, so mentor guidance and weekly traction planning are still disconnected.',
            'url' => $urls['lms'],
            'cta' => 'Open LMS',
        ];
    }

    $bazaarSnapshot = !empty($sources['bazaar']['snapshot']) && is_array($sources['bazaar']['snapshot']) ? $sources['bazaar']['snapshot'] : [];
    if (!empty($sources['bazaar'])) {
        if ((int) ($bazaarSnapshot['product_count'] ?? 0) === 0) {
            $actions[] = [
                'priority' => 96,
                'platform' => 'bazaar',
                'title' => 'Add the first product in Bazaar',
                'reason' => 'The store is connected but still has no products, so founders cannot test demand or start selling.',
                'url' => $urls['bazaar'] . '/admin/products',
                'cta' => 'Add products',
            ];
        }
        if (empty($bazaarSnapshot['website_title']) || empty($bazaarSnapshot['theme_template'])) {
            $actions[] = [
                'priority' => 72,
                'platform' => 'bazaar',
                'title' => 'Finish store basics and theme setup',
                'reason' => 'The storefront still needs a clearer title or theme configuration before it feels launch-ready.',
                'url' => $urls['bazaar'] . '/admin/basic_settings',
                'cta' => 'Update store settings',
            ];
        }
    }

    $servioSnapshot = !empty($sources['servio']['snapshot']) && is_array($sources['servio']['snapshot']) ? $sources['servio']['snapshot'] : [];
    if (!empty($sources['servio'])) {
        if ((int) ($servioSnapshot['service_count'] ?? 0) === 0) {
            $actions[] = [
                'priority' => 96,
                'platform' => 'servio',
                'title' => 'Add the first service in Servio',
                'reason' => 'The service site is connected but still has no services, so bookings cannot start.',
                'url' => $urls['servio'] . '/admin/services',
                'cta' => 'Add services',
            ];
        }
        if ((int) ($servioSnapshot['booking_count'] ?? 0) === 0 && (int) ($servioSnapshot['service_count'] ?? 0) > 0) {
            $actions[] = [
                'priority' => 78,
                'platform' => 'servio',
                'title' => 'Set up booking readiness',
                'reason' => 'Services exist, but there are no bookings yet. Working hours, service setup, and offer clarity should be reviewed.',
                'url' => $urls['servio'] . '/admin/basic_settings',
                'cta' => 'Open service settings',
            ];
        }
    }

    if (empty($sources['bazaar']) && empty($sources['servio'])) {
        $actions[] = [
            'priority' => 84,
            'platform' => 'atlas',
            'title' => 'Choose the right builder path',
            'reason' => 'The founder has not yet activated Bazaar or Servio in the shared intelligence layer, so the operating model is still unclear.',
            'url' => $urls['atlas'] . '/build-website',
            'cta' => 'Choose builder path',
        ];
    }

    if ($atlasImagesUsed === 0 && $hasCompanyBasics) {
        $actions[] = [
            'priority' => 66,
            'platform' => 'atlas',
            'title' => 'Generate the first marketing assets',
            'reason' => 'The business profile is ready, but Atlas has not produced any social assets yet, so top-of-funnel momentum is still missing.',
            'url' => $urls['atlas'] . '/ai-images/campaign',
            'cta' => 'Create campaign assets',
        ];
    }

    usort($actions, function ($left, $right) {
        return (int) $right['priority'] <=> (int) $left['priority'];
    });

    return array_slice($actions, 0, max(1, (int) $limit));
}

function hatchers_get_founder_action_plan_text($userId, $limit = 5)
{
    $actions = hatchers_get_founder_action_plan($userId, $limit);
    if (empty($actions)) {
        return '';
    }

    $lines = [];
    foreach ($actions as $index => $action) {
        $lines[] = ($index + 1) . '. [' . strtoupper($action['platform']) . '] ' . $action['title'] . ' - ' . $action['reason'];
    }

    return implode("\n", $lines);
}

function hatchers_get_pending_write_action($userId)
{
    return hatchers_decode_json_option($userId, 'hatchers_pending_write_action', []);
}

function hatchers_set_pending_write_action($userId, array $action)
{
    update_user_option($userId, 'hatchers_pending_write_action', json_encode($action));
    return $action;
}

function hatchers_clear_pending_write_action($userId)
{
    update_user_option($userId, 'hatchers_pending_write_action', '');
}

function hatchers_get_action_drafts($userId)
{
    return hatchers_decode_json_option($userId, 'hatchers_action_drafts', []);
}

function hatchers_get_campaign_records($userId)
{
    return hatchers_decode_json_option($userId, 'hatchers_campaign_records', []);
}

function hatchers_save_campaign_records($userId, array $campaigns)
{
    update_user_option($userId, 'hatchers_campaign_records', json_encode(array_slice(array_values($campaigns), -50)));
}

function hatchers_filter_campaign_records(array $campaigns, $includeArchived = false, $onlyArchived = false)
{
    return array_values(array_filter($campaigns, function ($campaign) use ($includeArchived, $onlyArchived) {
        $status = trim((string) ($campaign['status'] ?? 'drafted'));
        $isArchived = $status === 'archived';

        if ($onlyArchived) {
            return $isArchived;
        }

        if ($includeArchived) {
            return true;
        }

        return !$isArchived;
    }));
}

function hatchers_get_recent_campaign_records($userId, $limit = 3, $includeArchived = false)
{
    $campaigns = hatchers_filter_campaign_records(
        hatchers_get_campaign_records($userId),
        $includeArchived,
        false
    );
    usort($campaigns, function ($left, $right) {
        return strtotime(isset($right['updated_at']) ? $right['updated_at'] : '') <=> strtotime(isset($left['updated_at']) ? $left['updated_at'] : '');
    });

    return array_slice($campaigns, 0, max(1, (int) $limit));
}

function hatchers_get_archived_campaign_records($userId, $limit = 6)
{
    $campaigns = hatchers_filter_campaign_records(
        hatchers_get_campaign_records($userId),
        true,
        true
    );
    usort($campaigns, function ($left, $right) {
        return strtotime(isset($right['updated_at']) ? $right['updated_at'] : '') <=> strtotime(isset($left['updated_at']) ? $left['updated_at'] : '');
    });

    return array_slice($campaigns, 0, max(1, (int) $limit));
}

function hatchers_find_campaign_record(array $campaigns, $targetName = '')
{
    if (empty($campaigns)) {
        return [null, null];
    }

    $targetName = mb_strtolower(trim((string) $targetName));
    if ($targetName === '') {
        $lastIndex = count($campaigns) - 1;
        return [isset($campaigns[$lastIndex]) ? $campaigns[$lastIndex] : null, $lastIndex];
    }

    foreach ($campaigns as $index => $campaign) {
        $title = mb_strtolower(trim((string) ($campaign['title'] ?? '')));
        if ($title === $targetName) {
            return [$campaign, $index];
        }
    }

    return [null, null];
}

function hatchers_get_campaign_record_by_id($userId, $campaignId = '')
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return null;
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) === $campaignId) {
            return $campaign;
        }
    }

    return null;
}

function hatchers_campaign_edit_url()
{
    $urls = hatchers_get_platform_urls();
    return $urls['atlas'] . '/ai-images/campaign';
}

function hatchers_campaign_detail_url($campaignId = '')
{
    $urls = hatchers_get_platform_urls();
    $base = $urls['atlas'] . '/ai-images/campaign-detail';
    $campaignId = trim((string) $campaignId);

    if ($campaignId === '') {
        return $base;
    }

    return $base . '?campaign_id=' . rawurlencode($campaignId);
}

function hatchers_campaign_record_url($campaignId = '')
{
    return hatchers_campaign_detail_url($campaignId);
}

function hatchers_create_campaign_record($userId, array $payload)
{
    $campaigns = hatchers_get_campaign_records($userId);
    $now = date('Y-m-d H:i:s');
    $title = trim((string) ($payload['title'] ?? ''));
    if ($title === '') {
        $title = 'Campaign brief';
    }

    $campaign = [
        'id' => hatchers_make_action_id(),
        'title' => $title,
        'description' => trim((string) ($payload['description'] ?? 'Created from Hatchers OS by Atlas.')),
        'actor_role' => trim((string) ($payload['actor_role'] ?? 'founder')),
        'status' => 'drafted',
        'created_at' => $now,
        'updated_at' => $now,
    ];

    $campaigns[] = $campaign;
    hatchers_save_campaign_records($userId, $campaigns);

    hatchers_update_founder_intelligence($userId, [
        'app' => 'atlas',
        'role' => $campaign['actor_role'],
        'operations' => [
            'campaigns' => [
                'count' => count($campaigns),
                'latest' => [
                    'id' => $campaign['id'],
                    'title' => $campaign['title'],
                    'updated_at' => $campaign['updated_at'],
                ],
            ],
        ],
        'sync_summary' => 'Atlas created a native campaign brief "' . $campaign['title'] . '"',
    ]);

    hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
        'activity' => 'Atlas campaign brief created.',
        'latest_content_summary' => $campaign['title'],
    ]);

    return [
        'success' => true,
        'record_id' => $campaign['id'],
        'title' => $campaign['title'],
        'edit_url' => hatchers_campaign_record_url($campaign['id']),
        'reply' => 'Done. I created that campaign brief natively in Atlas and synced it back into Hatchers OS.',
    ];
}

function hatchers_update_campaign_record($userId, array $payload)
{
    $campaigns = hatchers_get_campaign_records($userId);
    list($campaign, $index) = hatchers_find_campaign_record($campaigns, isset($payload['target_name']) ? $payload['target_name'] : '');
    if (empty($campaign) || $index === null) {
        return [
            'success' => false,
            'error' => 'The requested campaign was not found in Atlas.',
        ];
    }

    $field = trim((string) ($payload['field'] ?? ''));
    $value = trim((string) ($payload['value'] ?? ''));
    if ($field === '' || $value === '') {
        return [
            'success' => false,
            'error' => 'Update field and value are required.',
        ];
    }

    if ($field === 'title') {
        $campaign['title'] = $value;
    } elseif (in_array($field, ['description', 'content'], true)) {
        $campaign['description'] = $value;
    } else {
        return [
            'success' => false,
            'error' => 'Unsupported campaign field update.',
        ];
    }

    $campaign['updated_at'] = date('Y-m-d H:i:s');
    $campaigns[$index] = $campaign;
    hatchers_save_campaign_records($userId, $campaigns);

    hatchers_update_founder_intelligence($userId, [
        'app' => 'atlas',
        'role' => trim((string) ($payload['actor_role'] ?? 'founder')),
        'operations' => [
            'campaigns' => [
                'count' => count($campaigns),
                'latest' => [
                    'id' => $campaign['id'],
                    'title' => $campaign['title'],
                    'updated_at' => $campaign['updated_at'],
                ],
            ],
        ],
        'sync_summary' => 'Atlas updated a native campaign brief "' . $campaign['title'] . '"',
    ]);

    hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
        'activity' => 'Atlas campaign brief updated.',
        'latest_content_summary' => $campaign['title'],
    ]);

    return [
        'success' => true,
        'record_id' => $campaign['id'],
        'title' => $campaign['title'],
        'edit_url' => hatchers_campaign_edit_url(),
        'reply' => 'Done. I updated that campaign brief natively in Atlas and synced it back into Hatchers OS.',
    ];
}

function hatchers_update_campaign_detail($userId, $campaignId, array $payload)
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return [
            'success' => false,
            'error' => 'Campaign id is required.',
        ];
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $index => $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) !== $campaignId) {
            continue;
        }

        $title = trim((string) ($payload['title'] ?? ($campaign['title'] ?? '')));
        if ($title === '') {
            return [
                'success' => false,
                'error' => 'Campaign title is required.',
            ];
        }

        $campaign['title'] = $title;
        $campaign['description'] = trim((string) ($payload['description'] ?? ($campaign['description'] ?? '')));
        $campaign['updated_at'] = date('Y-m-d H:i:s');

        $cleanState = [
            'campaign_type' => trim((string) ($payload['campaign_type'] ?? ($campaign['form_state']['campaign_type'] ?? ''))),
            'funnel_stage' => trim((string) ($payload['funnel_stage'] ?? ($campaign['form_state']['funnel_stage'] ?? ''))),
            'focus_area' => trim((string) ($payload['focus_area'] ?? ($campaign['form_state']['focus_area'] ?? ''))),
            'content_angle' => trim((string) ($payload['content_angle'] ?? ($campaign['form_state']['content_angle'] ?? ''))),
            'use_case' => trim((string) ($payload['use_case'] ?? ($campaign['form_state']['use_case'] ?? ''))),
            'grid_style' => trim((string) ($payload['grid_style'] ?? ($campaign['form_state']['grid_style'] ?? ''))),
            'description' => trim((string) ($payload['strategy_notes'] ?? ($campaign['form_state']['description'] ?? ''))),
        ];

        $campaign['form_state'] = hatchers_compact_array($cleanState);
        $campaigns[$index] = $campaign;
        hatchers_save_campaign_records($userId, $campaigns);

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => trim((string) ($payload['actor_role'] ?? 'founder')),
            'operations' => [
                'campaigns' => [
                    'count' => count($campaigns),
                    'latest' => [
                        'id' => $campaign['id'],
                        'title' => $campaign['title'],
                        'updated_at' => $campaign['updated_at'],
                    ],
                ],
            ],
            'sync_summary' => 'Atlas updated campaign detail for "' . $campaign['title'] . '"',
        ]);

        hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
            'activity' => 'Atlas campaign detail updated.',
            'latest_content_summary' => $campaign['title'],
        ]);

        return [
            'success' => true,
            'record_id' => $campaign['id'],
            'title' => $campaign['title'],
            'edit_url' => hatchers_campaign_record_url($campaign['id']),
            'reply' => 'Done. I updated the campaign detail and synced it back into Hatchers OS.',
        ];
    }

    return [
        'success' => false,
        'error' => 'The requested campaign was not found in Atlas.',
    ];
}

function hatchers_duplicate_campaign_record($userId, $campaignId)
{
    $campaign = hatchers_get_campaign_record_by_id($userId, $campaignId);
    if (empty($campaign)) {
        return [
            'success' => false,
            'error' => 'The requested campaign was not found in Atlas.',
        ];
    }

    $title = trim((string) ($campaign['title'] ?? 'Campaign brief'));
    $payload = [
        'title' => $title . ' Copy',
        'description' => trim((string) ($campaign['description'] ?? '')),
        'actor_role' => trim((string) ($campaign['actor_role'] ?? 'founder')),
    ];

    $result = hatchers_create_campaign_record($userId, $payload);
    if (empty($result['success']) || empty($result['record_id'])) {
        return $result;
    }

    hatchers_update_campaign_detail($userId, $result['record_id'], [
        'title' => $payload['title'],
        'description' => $payload['description'],
        'campaign_type' => $campaign['form_state']['campaign_type'] ?? '',
        'funnel_stage' => $campaign['form_state']['funnel_stage'] ?? '',
        'focus_area' => $campaign['form_state']['focus_area'] ?? '',
        'content_angle' => $campaign['form_state']['content_angle'] ?? '',
        'use_case' => $campaign['form_state']['use_case'] ?? '',
        'grid_style' => $campaign['form_state']['grid_style'] ?? '',
        'strategy_notes' => $campaign['form_state']['description'] ?? '',
        'actor_role' => $payload['actor_role'],
    ]);

    return [
        'success' => true,
        'record_id' => $result['record_id'],
        'title' => $payload['title'],
        'edit_url' => hatchers_campaign_record_url($result['record_id']),
        'reply' => 'Done. I duplicated that campaign in Atlas.',
    ];
}

function hatchers_archive_campaign_record($userId, $campaignId)
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return [
            'success' => false,
            'error' => 'Campaign id is required.',
        ];
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $index => $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) !== $campaignId) {
            continue;
        }

        $campaign['status'] = 'archived';
        $campaign['archived_at'] = date('Y-m-d H:i:s');
        $campaign['updated_at'] = $campaign['archived_at'];
        $campaigns[$index] = $campaign;
        hatchers_save_campaign_records($userId, $campaigns);

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => 'founder',
            'operations' => [
                'campaigns' => [
                    'count' => count($campaigns),
                    'latest' => [
                        'id' => $campaign['id'],
                        'title' => $campaign['title'],
                        'updated_at' => $campaign['updated_at'],
                    ],
                ],
            ],
            'sync_summary' => 'Atlas archived campaign "' . $campaign['title'] . '"',
        ]);

        hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
            'activity' => 'Atlas campaign archived.',
            'latest_content_summary' => $campaign['title'],
        ]);

        return [
            'success' => true,
            'record_id' => $campaign['id'],
            'title' => $campaign['title'],
            'edit_url' => hatchers_campaign_record_url($campaign['id']),
            'reply' => 'Done. I archived that campaign in Atlas.',
        ];
    }

    return [
        'success' => false,
        'error' => 'The requested campaign was not found in Atlas.',
    ];
}

function hatchers_restore_campaign_record($userId, $campaignId)
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return [
            'success' => false,
            'error' => 'Campaign id is required.',
        ];
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $index => $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) !== $campaignId) {
            continue;
        }

        $campaign['status'] = 'drafted';
        $campaign['restored_at'] = date('Y-m-d H:i:s');
        $campaign['updated_at'] = $campaign['restored_at'];
        unset($campaign['archived_at']);
        $campaigns[$index] = $campaign;
        hatchers_save_campaign_records($userId, $campaigns);

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => 'founder',
            'operations' => [
                'campaigns' => [
                    'count' => count($campaigns),
                    'latest' => [
                        'id' => $campaign['id'],
                        'title' => $campaign['title'],
                        'updated_at' => $campaign['updated_at'],
                    ],
                ],
            ],
            'sync_summary' => 'Atlas restored campaign "' . $campaign['title'] . '"',
        ]);

        hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
            'activity' => 'Atlas campaign restored.',
            'latest_content_summary' => $campaign['title'],
        ]);

        return [
            'success' => true,
            'record_id' => $campaign['id'],
            'title' => $campaign['title'],
            'edit_url' => hatchers_campaign_record_url($campaign['id']),
            'reply' => 'Done. I restored that campaign in Atlas.',
        ];
    }

    return [
        'success' => false,
        'error' => 'The requested campaign was not found in Atlas.',
    ];
}

function hatchers_update_campaign_form_state($userId, $campaignId, array $formState)
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return false;
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $index => $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) !== $campaignId) {
            continue;
        }

        $cleanState = [
            'campaign_type' => trim((string) ($formState['campaign_type'] ?? '')),
            'funnel_stage' => trim((string) ($formState['funnel_stage'] ?? '')),
            'focus_area' => trim((string) ($formState['focus_area'] ?? '')),
            'content_angle' => trim((string) ($formState['content_angle'] ?? '')),
            'use_case' => trim((string) ($formState['use_case'] ?? '')),
            'grid_style' => trim((string) ($formState['grid_style'] ?? '')),
            'description' => trim((string) ($formState['description'] ?? '')),
        ];

        $campaign['form_state'] = hatchers_compact_array($cleanState);
        $campaign['updated_at'] = date('Y-m-d H:i:s');
        $campaigns[$index] = $campaign;
        hatchers_save_campaign_records($userId, $campaigns);

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => 'founder',
            'operations' => [
                'campaigns' => [
                    'count' => count($campaigns),
                    'latest' => [
                        'id' => $campaign['id'],
                        'title' => $campaign['title'],
                        'updated_at' => $campaign['updated_at'],
                    ],
                ],
            ],
            'sync_summary' => 'Atlas saved campaign form state for "' . $campaign['title'] . '"',
        ]);

        hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
            'activity' => 'Atlas campaign strategy selections saved.',
            'latest_content_summary' => $campaign['title'],
        ]);

        return true;
    }

    return false;
}

function hatchers_record_campaign_generation($userId, $campaignId, array $payload = [])
{
    $campaignId = trim((string) $campaignId);
    if ($campaignId === '') {
        return false;
    }

    $campaigns = hatchers_get_campaign_records($userId);
    foreach ($campaigns as $index => $campaign) {
        if (trim((string) ($campaign['id'] ?? '')) !== $campaignId) {
            continue;
        }

        $generation = [
            'batch_key' => trim((string) ($payload['batch_key'] ?? '')),
            'post_count' => max(0, (int) ($payload['post_count'] ?? 0)),
            'generator' => trim((string) ($payload['generator'] ?? 'campaign')),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $history = !empty($campaign['recent_generations']) && is_array($campaign['recent_generations'])
            ? $campaign['recent_generations']
            : [];
        $history[] = $generation;

        $campaign['recent_generations'] = array_slice($history, -6);
        $campaign['last_batch_key'] = $generation['batch_key'];
        $campaign['last_generated_at'] = $generation['created_at'];
        $campaign['generated_posts_count'] = max(
            (int) ($campaign['generated_posts_count'] ?? 0),
            0
        ) + $generation['post_count'];
        $campaign['updated_at'] = $generation['created_at'];

        $campaigns[$index] = $campaign;
        hatchers_save_campaign_records($userId, $campaigns);

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => 'founder',
            'operations' => [
                'campaigns' => [
                    'count' => count($campaigns),
                    'latest' => [
                        'id' => $campaign['id'],
                        'title' => $campaign['title'],
                        'updated_at' => $campaign['updated_at'],
                    ],
                ],
            ],
            'sync_summary' => 'Atlas generated posts for campaign "' . $campaign['title'] . '"',
        ]);

        hatchers_push_os_snapshot($userId, 'atlas_campaign_brief', [
            'activity' => 'Atlas generated campaign posts.',
            'latest_content_summary' => $campaign['title'],
        ]);

        return true;
    }

    return false;
}

function hatchers_execute_external_action($userId, array $payload)
{
    $category = trim((string) ($payload['category'] ?? ''));
    $operation = trim((string) ($payload['operation'] ?? 'create'));

    if ($category !== 'campaign') {
        return [
            'success' => false,
            'error' => 'Unsupported Atlas action category.',
        ];
    }

    if ($operation === 'archive') {
        return hatchers_archive_campaign_record($userId, isset($payload['campaign_id']) ? $payload['campaign_id'] : '');
    }

    if ($operation === 'restore') {
        return hatchers_restore_campaign_record($userId, isset($payload['campaign_id']) ? $payload['campaign_id'] : '');
    }

    if ($operation === 'duplicate') {
        return hatchers_duplicate_campaign_record($userId, isset($payload['campaign_id']) ? $payload['campaign_id'] : '');
    }

    if ($operation === 'update') {
        return hatchers_update_campaign_record($userId, $payload);
    }

    return hatchers_create_campaign_record($userId, $payload);
}

function hatchers_detect_actor_role_from_text($message, $fallback = '')
{
    $message = strtolower(trim((string) $message));
    if ($message === '') {
        return trim((string) $fallback);
    }

    if (preg_match('/\bmentor\b/', $message)) {
        return 'mentor';
    }

    if (preg_match('/\bfounder\b/', $message)) {
        return 'founder';
    }

    return trim((string) $fallback);
}

function hatchers_is_confirmation_message($message)
{
    $message = strtolower(trim((string) $message));
    if ($message === '') {
        return false;
    }

    foreach (['yes', 'confirm', 'confirmed', 'proceed', 'go ahead', 'do it', 'continue', 'approved'] as $needle) {
        if (strpos($message, $needle) !== false) {
            return true;
        }
    }

    return false;
}

function hatchers_is_rejection_message($message)
{
    $message = strtolower(trim((string) $message));
    if ($message === '') {
        return false;
    }

    foreach (['cancel', 'stop', 'never mind', 'dont', "don't", 'no '] as $needle) {
        if (strpos($message, $needle) !== false) {
            return true;
        }
    }

    return $message === 'no';
}

function hatchers_make_action_id()
{
    return uniqid('hatchers_action_', true);
}

function hatchers_build_write_action_from_message($message)
{
    $message = trim((string) $message);
    if ($message === '') {
        return [];
    }

    $fieldPatterns = [
        'company_name' => '/\b(?:set|update|change)\s+(?:the\s+)?company\s+name\s+to\s+(.+)/i',
        'company_description' => '/\b(?:set|update|change)\s+(?:the\s+)?company\s+description\s+to\s+(.+)/i',
        'target_audience' => '/\b(?:set|update|change)\s+(?:the\s+)?target\s+audience\s+to\s+(.+)/i',
        'ideal_customer_profile' => '/\b(?:set|update|change)\s+(?:the\s+)?(?:ideal\s+customer\s+profile|icp)\s+to\s+(.+)/i',
        'brand_voice' => '/\b(?:set|update|change)\s+(?:the\s+)?brand\s+voice\s+to\s+(.+)/i',
        'differentiators' => '/\b(?:set|update|change)\s+(?:the\s+)?differentiators?\s+to\s+(.+)/i',
        'content_goals' => '/\b(?:set|update|change)\s+(?:the\s+)?content\s+goals?\s+to\s+(.+)/i',
    ];

    foreach ($fieldPatterns as $field => $pattern) {
        if (preg_match($pattern, $message, $matches)) {
            $value = trim((string) ($matches[1] ?? ''));
            $value = rtrim($value, ". \t\n\r\0\x0B");

            if ($value !== '') {
                return [
                    'type' => 'company_field_update',
                    'field' => $field,
                    'value' => $value,
                    'summary' => 'update the founder company profile field "' . str_replace('_', ' ', $field) . '"',
                ];
            }
        }
    }

    if (hatchers_is_instructional_question($message)) {
        return [];
    }

    if (!preg_match('/\b(add|create|draft|write|prepare|make)\b/i', $message)) {
        return [];
    }

    $categoryMap = [
        'product' => ['platform' => 'bazaar', 'label' => 'product draft'],
        'service' => ['platform' => 'servio', 'label' => 'service draft'],
        'blog' => ['platform' => 'bazaar', 'label' => 'blog draft'],
        'page' => ['platform' => 'servio', 'label' => 'page draft'],
        'campaign' => ['platform' => 'atlas', 'label' => 'campaign brief'],
        'task' => ['platform' => 'lms', 'label' => 'mentor task draft'],
        'milestone' => ['platform' => 'lms', 'label' => 'milestone draft'],
    ];

    foreach ($categoryMap as $keyword => $meta) {
        if (preg_match('/\b' . preg_quote($keyword, '/') . 's?\b/i', $message)) {
            return [
                'type' => 'draft_record',
                'platform' => $meta['platform'],
                'category' => $keyword,
                'title' => ucfirst($meta['label']),
                'request' => $message,
                'summary' => 'create a ' . $meta['label'] . ' in shared Hatchers intelligence',
            ];
        }
    }

    return [];
}

function hatchers_is_instructional_question($message)
{
    $message = trim((string) $message);
    if ($message === '') {
        return false;
    }

    $patterns = [
        '/^\s*how\s+to\b/i',
        '/^\s*how\s+do\s+i\b/i',
        '/^\s*where\s+do\s+i\b/i',
        '/^\s*what\s+is\s+the\s+best\s+way\s+to\b/i',
        '/^\s*what\s+is\s+the\s+process\s+for\b/i',
        '/^\s*can\s+you\s+show\s+me\s+how\s+to\b/i',
        '/^\s*can\s+you\s+tell\s+me\s+how\s+to\b/i',
        '/^\s*walk\s+me\s+through\b/i',
        '/^\s*explain\s+how\s+to\b/i',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $message)) {
            return true;
        }
    }

    return substr($message, -1) === '?';
}

function hatchers_execute_write_action($userId, array $action)
{
    $type = trim((string) ($action['type'] ?? ''));
    $actorRole = trim((string) ($action['actor_role'] ?? ''));
    $now = date('Y-m-d H:i:s');

    if ($type === 'company_field_update') {
        $field = trim((string) ($action['field'] ?? ''));
        $value = trim((string) ($action['value'] ?? ''));

        if ($field === '' || $value === '') {
            return [
                'success' => false,
                'reply' => "I couldn't complete that update because the field or value was missing.",
            ];
        }

        $profile = function_exists('social_media_get_profile') ? social_media_get_profile($userId) : [];
        if (!is_array($profile)) {
            $profile = [];
        }
        $profile[$field] = $value;

        if (function_exists('social_media_save_profile')) {
            social_media_save_profile($userId, $profile);
        }

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => $actorRole !== '' ? $actorRole : 'founder',
            'company' => [$field => $value],
            'sync_summary' => 'Atlas write action updated company field ' . $field,
        ]);

        return [
            'success' => true,
            'reply' => 'Done. I updated "' . str_replace('_', ' ', $field) . '" in the shared founder intelligence. This context is now available to Atlas and the connected Hatchers tools.',
        ];
    }

    if ($type === 'draft_record') {
        if (trim((string) ($action['category'] ?? '')) === 'campaign') {
            $result = hatchers_create_campaign_record($userId, [
                'title' => trim((string) ($action['title'] ?? 'Campaign brief')),
                'description' => trim((string) ($action['request'] ?? '')),
                'actor_role' => $actorRole !== '' ? $actorRole : 'founder',
            ]);

            return [
                'success' => !empty($result['success']),
                'reply' => !empty($result['success'])
                    ? ($result['reply'] ?? 'Done. I created that campaign brief in Atlas.')
                    : ($result['error'] ?? 'I could not create that campaign brief in Atlas right now.'),
            ];
        }

        $drafts = hatchers_get_action_drafts($userId);
        $draft = [
            'id' => hatchers_make_action_id(),
            'platform' => trim((string) ($action['platform'] ?? 'atlas')),
            'category' => trim((string) ($action['category'] ?? 'draft')),
            'title' => trim((string) ($action['title'] ?? 'Action draft')),
            'request' => trim((string) ($action['request'] ?? '')),
            'actor_role' => $actorRole !== '' ? $actorRole : 'founder',
            'status' => 'drafted',
            'created_at' => $now,
        ];
        $drafts[] = $draft;
        update_user_option($userId, 'hatchers_action_drafts', json_encode(array_slice($drafts, -50)));

        hatchers_update_founder_intelligence($userId, [
            'app' => 'atlas',
            'role' => $draft['actor_role'],
            'operations' => [
                'last_draft' => [
                    'platform' => $draft['platform'],
                    'category' => $draft['category'],
                    'title' => $draft['title'],
                    'created_at' => $draft['created_at'],
                ],
            ],
            'sync_summary' => 'Atlas write action created a ' . $draft['category'] . ' draft',
        ]);

        return [
            'success' => true,
            'reply' => 'Done. I created a shared draft for this ' . $draft['category'] . ' request in Atlas intelligence so it can be referenced across the Hatchers tools and agents.',
        ];
    }

    return [
        'success' => false,
        'reply' => "I understood the request as a write action, but I don't support executing that action yet.",
    ];
}

function hatchers_handle_write_action_message($userId, $message, array $context = [])
{
    $message = trim((string) $message);
    if ($message === '') {
        return ['handled' => false];
    }

    $pending = hatchers_get_pending_write_action($userId);
    $detectedRole = hatchers_detect_actor_role_from_text($message, trim((string) ($context['role'] ?? '')));

    if (!empty($pending)) {
        if (hatchers_is_rejection_message($message)) {
            hatchers_clear_pending_write_action($userId);
            return [
                'handled' => true,
                'reply' => 'Understood. I canceled that pending Atlas write action and did not change anything.',
                'executed' => false,
            ];
        }

        if (empty($pending['actor_role']) && $detectedRole !== '') {
            $pending['actor_role'] = $detectedRole;
            hatchers_set_pending_write_action($userId, $pending);
        }

        if (empty($pending['actor_role'])) {
            return [
                'handled' => true,
                'reply' => 'Before I perform this write action, are you acting as the founder or the mentor? Reply with "Founder, yes" or "Mentor, yes" to proceed.',
                'executed' => false,
            ];
        }

        if (!hatchers_is_confirmation_message($message)) {
            return [
                'handled' => true,
                'reply' => 'I have the action ready. Reply "Yes" to proceed as ' . $pending['actor_role'] . ', or say "cancel" if you want me to stop.',
                'executed' => false,
            ];
        }

        $execution = hatchers_execute_write_action($userId, $pending);
        hatchers_clear_pending_write_action($userId);

        return [
            'handled' => true,
            'reply' => $execution['reply'],
            'executed' => !empty($execution['success']),
        ];
    }

    $action = hatchers_build_write_action_from_message($message);
    if (empty($action)) {
        return ['handled' => false];
    }

    $action['actor_role'] = $detectedRole;
    $action['created_at'] = date('Y-m-d H:i:s');
    hatchers_set_pending_write_action($userId, $action);

    if ($detectedRole === '') {
        return [
            'handled' => true,
            'reply' => 'I can do that, but I need one safety check first. Are you acting as the founder or the mentor? Reply with "Founder, yes" or "Mentor, yes" and I will proceed.',
            'executed' => false,
        ];
    }

    return [
        'handled' => true,
        'reply' => 'I am ready to ' . $action['summary'] . '. Please reply "Yes" to proceed as ' . $detectedRole . ', or say "cancel" to stop.',
        'executed' => false,
    ];
}

function hatchers_enrich_prompt_with_intelligence($prompt, $userId, $taskLabel = 'Atlas task', $currentApp = 'atlas')
{
    $context = trim((string) hatchers_get_founder_intelligence_text($userId));
    $actions = trim((string) hatchers_get_founder_action_plan_text($userId, 5));
    if ($context === '') {
        return $prompt;
    }

    return trim(
        $prompt
        . "\n\nShared Hatchers intelligence for this founder:\n"
        . $context
        . "\n\nInstructions:\n"
        . "- Use this context when it improves relevance.\n"
        . "- Stay aligned with the founder's company, audience, offer, traction, and current workspace.\n"
        . "- If the request is marketing-related, tailor it to the founder's real business instead of giving generic output.\n"
        . "- Never claim that a write action has been completed unless it was explicitly confirmed and executed.\n"
        . ($actions !== '' ? "- Prefer the current highest-value next actions when suggesting execution priorities.\n" : '')
        . "- If the task benefits from another Hatchers tool, mention the right product naturally.\n"
        . "- Current app: " . $currentApp . "\n"
        . "- Current task: " . $taskLabel
        . ($actions !== '' ? "\n\nRecommended next actions:\n" . $actions : '')
    );
}

function hatchers_enrich_system_prompt_with_intelligence($systemPrompt, $userId, $taskLabel = 'Atlas task', $currentApp = 'atlas')
{
    $context = trim((string) hatchers_get_founder_intelligence_text($userId));
    $actions = trim((string) hatchers_get_founder_action_plan_text($userId, 5));
    if ($context === '') {
        return $systemPrompt;
    }

    return trim(
        $systemPrompt
        . "\n\nShared Hatchers intelligence:\n"
        . $context
        . "\n\nSystem rules:\n"
        . "- Personalize responses to the founder's actual company and current stage.\n"
        . "- Reuse known context instead of asking the founder to repeat themselves.\n"
        . "- When relevant, connect advice across LMS, Bazaar, Servio, and Atlas.\n"
        . "- Never perform or imply a write action without explicit confirmation.\n"
        . "- If a write action is requested, first confirm whether the user is acting as founder or mentor when that matters.\n"
        . ($actions !== '' ? "- Prefer the current action plan when recommending the next best step.\n" : '')
        . "- Do not invent facts beyond the stored intelligence.\n"
        . "- Current app: " . $currentApp . "\n"
        . "- Current task: " . $taskLabel
        . ($actions !== '' ? "\n\nCurrent action plan:\n" . $actions : '')
    );
}
