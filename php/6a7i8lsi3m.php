<?php
/**
 * Atlas user AJAX controller.
 */

define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH . "/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_' . $config['lang'] . '.php';

sec_session_start();

if (isset($_GET['action'])) {
    if ($_GET['action'] == "submitBlogComment") {
        submitBlogComment();
    }
    if ($_GET['action'] == "generate_content") {
        generate_content();
    }
    if ($_GET['action'] == "generate_image") {
        generate_image();
    }
    if ($_GET['action'] == "generate_instagram_grid") {
        generate_instagram_grid();
    }
    if ($_GET['action'] == "regenerate_social_post") {
        regenerate_social_post();
    }
    if ($_GET['action'] == "vote_social_post_image") {
        vote_social_post_image();
    }
    if ($_GET['action'] == "save_social_post_overlay") {
        save_social_post_overlay();
    }
    if ($_GET['action'] == "save_document") {
        save_document();
    }
    if ($_GET['action'] == "delete_document") {
        delete_document();
    }
    if ($_GET['action'] == "delete_image") {
        delete_image();
    }

    // AI chat
    if ($_GET['action'] == "load_ai_chats") {
        load_ai_chats();
    }
    if ($_GET['action'] == "edit_conversation_title") {
        edit_conversation_title();
    }
    if ($_GET['action'] == "send_ai_message") {
        send_ai_message();
    }
    if ($_GET['action'] == "chat_stream") {
        chat_stream();
    }
    if ($_GET['action'] == "delete_ai_chats") {
        delete_ai_chats();
    }
    if ($_GET['action'] == "export_ai_chats") {
        export_ai_chats();
    }

    // speech to text
    if ($_GET['action'] == "speech_to_text") {
        speech_to_text();
    }

    // ai code
    if ($_GET['action'] == "ai_code") {
        ai_code();
    }

    // text to speech
    if ($_GET['action'] == "text_to_speech") {
        text_to_speech();
    }
    if ($_GET['action'] == "delete_speech") {
        delete_speech();
    }
    if ($_GET['action'] == "upload_profile_media") {
        upload_profile_media();
    }
    if ($_GET['action'] == "extract_company_profile") {
        extract_company_profile();
    }
    if ($_GET['action'] == "refresh_company_intelligence") {
        refresh_company_intelligence();
    }
    if ($_GET['action'] == "generate_company_intelligence_field") {
        generate_company_intelligence_field();
    }
    if ($_GET['action'] == "save_company_intelligence_draft") {
        save_company_intelligence_draft();
    }
    die(0);
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == "ajaxlogin") {
        ajaxlogin();
    }
    if ($_POST['action'] == "email_verify") {
        email_verify();
    }
    if ($_POST['action'] == "add_email_subscriber") {
        add_email_subscriber();
    }
    die(0);
}

function ajaxlogin()
{
    global $config, $lang, $link;
    $loggedin = userlogin($_POST['username'], $_POST['password']);
    $result['success'] = false;
    $result['message'] = __("Error: Please try again.");
    if (!is_array($loggedin)) {
        $result['message'] = __("Username or Password not found");
    } elseif ($loggedin['status'] == 2) {
        $result['message'] = __("This account has been banned");
    } else {
        create_user_session($loggedin['id'], $loggedin['username'], $loggedin['password'], $loggedin['user_type']);
        update_lastactive();

        $redirect_url = get_option('after_login_link');
        if (empty($redirect_url)) {
            $redirect_url = $link['DASHBOARD'];
        }

        $result['success'] = true;
        $result['message'] = $redirect_url;
    }
    die(json_encode($result));
}

function email_verify()
{
    global $config, $lang;

    if (checkloggedin()) {
        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm", $_SESSION['user']['id']);

        $respond = __('Sent');
        echo '<a class="button gray" href="javascript:void(0);">' . $respond . '</a>';
        die();

    } else {
        exit;
    }
}

function upload_profile_media()
{
    global $config;
    $result = ['success' => false, 'error' => __('Unexpected error, please try again.')];

    if (!checkloggedin()) {
        die(json_encode($result));
    }

    if (!check_allow()) {
        $result['error'] = __('Disabled on demo');
        die(json_encode($result));
    }

    $type = !empty($_POST['type']) ? validate_input($_POST['type']) : '';

    if ($type === 'avatar') {
        if (empty($_FILES['avatar']['name'])) {
            $result['error'] = __('Please choose an image.');
            die(json_encode($result));
        }

        $mainPath = ROOTPATH . "/storage/profile/";
        $upload = quick_file_upload('avatar', $mainPath);
        if (!$upload['success']) {
            $result['error'] = $upload['error'];
            die(json_encode($result));
        }

        $fileName = $upload['file_name'];
        resizeImage(150, $mainPath . $fileName, $mainPath . $fileName);
        resizeImage(60, $mainPath . 'small_' . $fileName, $mainPath . $fileName);

        $person = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
        $oldImage = $person['image'];
        $person->set('image', $fileName);
        $person->set_expr('updated_at', 'NOW()');
        $person->save();

        if (!empty($oldImage) && $oldImage !== 'default_user.png') {
            foreach ([$mainPath . $oldImage, $mainPath . 'small_' . $oldImage] as $oldFile) {
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }

        $loggedin = get_user_data("", $_SESSION['user']['id']);
        create_user_session($loggedin['id'], $loggedin['username'], $loggedin['password'], $loggedin['user_type']);

        $result['success'] = true;
        $result['url'] = $config['site_url'] . 'storage/profile/' . $fileName;
        $result['small_url'] = $config['site_url'] . 'storage/profile/small_' . $fileName;
        die(json_encode($result));
    }

    if ($type === 'company_logo') {
        if (empty($_FILES['company_logo']['name'])) {
            $result['error'] = __('Please choose an image.');
            die(json_encode($result));
        }

        $profile = social_media_get_profile($_SESSION['user']['id']);
        $fileName = social_media_upload_company_logo($_SESSION['user']['id'], $profile['company_logo']);
        if (empty($fileName)) {
            $result['error'] = __('Unable to upload company logo.');
            die(json_encode($result));
        }

        $result['success'] = true;
        $result['url'] = $config['site_url'] . 'storage/company/' . $fileName;
        die(json_encode($result));
    }

    $result['error'] = __('Invalid upload type.');
    die(json_encode($result));
}

function refresh_company_intelligence()
{
    $result = ['success' => false, 'error' => __('Unexpected error, please try again.')];

    if (!checkloggedin()) {
        die(json_encode($result));
    }

    $intelligence = social_media_get_company_intelligence($_SESSION['user']['id'], true);
    if (empty($intelligence) || !is_array($intelligence)) {
        die(json_encode($result));
    }

    $result['success'] = true;
    $result['message'] = __('Company intelligence refreshed successfully.');
    $result['intelligence'] = [
        'refreshed_at' => !empty($intelligence['refreshed_at']) ? $intelligence['refreshed_at'] : '',
        'company_summary' => !empty($intelligence['company_summary']) ? $intelligence['company_summary'] : '',
        'market_research' => !empty($intelligence['market_research']) ? $intelligence['market_research'] : '',
        'competitive_edges' => !empty($intelligence['competitive_edges']) ? $intelligence['competitive_edges'] : '',
        'strategic_guidance' => !empty($intelligence['strategic_guidance']) ? $intelligence['strategic_guidance'] : '',
    ];
    die(json_encode($result));
}

function extract_company_profile()
{
    $result = ['success' => false, 'error' => __('Unexpected error, please try again.')];

    if (!checkloggedin()) {
        die(json_encode($result));
    }

    $website = !empty($_POST['website']) ? validate_input($_POST['website']) : '';
    if ($website === '') {
        $result['error'] = __('Please enter your website first.');
        die(json_encode($result));
    }

    $existingProfile = social_media_get_profile($_SESSION['user']['id']);
    $profile = social_media_extract_profile_from_website($website, $existingProfile);

    if (empty($profile) || !is_array($profile)) {
        die(json_encode($result));
    }

    $result['success'] = true;
    $result['message'] = __('Website extracted successfully.');
    $result['profile'] = $profile;
    die(json_encode($result));
}

function generate_company_intelligence_field()
{
    $result = ['success' => false, 'error' => __('Unexpected error, please try again.')];

    if (!checkloggedin()) {
        die(json_encode($result));
    }

    $field = !empty($_POST['field']) ? validate_input($_POST['field']) : '';
    if ($field === '') {
        $result['error'] = __('Please choose a field first.');
        die(json_encode($result));
    }

    $profile = social_media_get_profile($_SESSION['user']['id']);
    $context = [];
    foreach ([
        'company_name',
        'company_website',
        'company_industry',
        'company_description',
        'ideal_customer_profile',
        'target_audience',
        'differentiators',
        'brand_voice',
        'website_snapshot_json',
        'website_extracted_at',
    ] as $key) {
        if (isset($_POST[$key])) {
            $context[$key] = $_POST[$key];
        }
    }

    if (!empty($_POST['website_snapshot_json'])) {
        $snapshot = json_decode($_POST['website_snapshot_json'], true);
        if (is_array($snapshot)) {
            $context['website_snapshot'] = $snapshot;
        }
    }

    foreach ([
        'top_problems_solved',
        'unique_selling_points',
        'brand_colors',
        'visual_mood',
        'tone_attributes',
        'reference_brands',
        'competitors',
    ] as $listKey) {
        if (isset($_POST[$listKey])) {
            $context[$listKey] = $_POST[$listKey];
        }
    }

    $suggestion = social_media_generate_profile_field_suggestion($_SESSION['user']['id'], $field, array_merge($profile, $context));
    if ($suggestion === null) {
        $result['error'] = __('Atlas could not generate that field right now.');
        die(json_encode($result));
    }

    $result['success'] = true;
    $result['field'] = $field;
    $result['value'] = $suggestion;
    die(json_encode($result));
}

function save_company_intelligence_draft()
{
    $result = ['success' => false, 'error' => __('Unexpected error, please try again.')];

    if (!checkloggedin()) {
        die(json_encode($result));
    }

    $step = !empty($_POST['step']) ? max(1, min(4, (int) $_POST['step'])) : 1;
    $existingProfile = social_media_get_profile($_SESSION['user']['id']);
    $profileData = company_intelligence_collect_profile_payload($existingProfile);

    if (!empty($_FILES['company_logo']['name'])) {
        $profileData['company_logo'] = social_media_upload_company_logo($_SESSION['user']['id'], $existingProfile['company_logo']);
    } else {
        $profileData['company_logo'] = $existingProfile['company_logo'];
    }

    $profileData['moodboard_images'] = social_media_upload_moodboard_images($_SESSION['user']['id'], $profileData['moodboard_images']);
    $savedProfile = social_media_save_profile($_SESSION['user']['id'], $profileData);

    $result['success'] = true;
    $result['message'] = __('Step saved successfully.');
    $result['step'] = $step;
    $result['saved_at'] = date('Y-m-d H:i:s');
    $result['profile'] = [
        'company_name' => !empty($savedProfile['company_name']) ? $savedProfile['company_name'] : '',
        'company_website' => !empty($savedProfile['company_website']) ? $savedProfile['company_website'] : '',
        'company_industry' => !empty($savedProfile['company_industry']) ? $savedProfile['company_industry'] : '',
        'company_description' => !empty($savedProfile['company_description']) ? $savedProfile['company_description'] : '',
        'ideal_customer_profile' => !empty($savedProfile['ideal_customer_profile']) ? $savedProfile['ideal_customer_profile'] : '',
        'top_problems_solved' => !empty($savedProfile['top_problems_solved']) ? $savedProfile['top_problems_solved'] : [],
        'unique_selling_points' => !empty($savedProfile['unique_selling_points']) ? $savedProfile['unique_selling_points'] : [],
        'brand_colors' => !empty($savedProfile['brand_colors']) ? $savedProfile['brand_colors'] : [],
        'visual_mood' => !empty($savedProfile['visual_mood']) ? $savedProfile['visual_mood'] : [],
        'tone_attributes' => !empty($savedProfile['tone_attributes']) ? $savedProfile['tone_attributes'] : [],
        'reference_brands' => !empty($savedProfile['reference_brands']) ? $savedProfile['reference_brands'] : [],
        'competitors' => !empty($savedProfile['competitors']) ? $savedProfile['competitors'] : [],
        'moodboard_images' => !empty($savedProfile['moodboard_images']) ? $savedProfile['moodboard_images'] : [],
        'company_logo' => !empty($savedProfile['company_logo']) ? $savedProfile['company_logo'] : '',
    ];
    die(json_encode($result));
}

function company_intelligence_collect_profile_payload($existingProfile)
{
    $profileData = [
        'founder_name' => isset($_POST['founder_name']) ? validate_input($_POST['founder_name']) : $existingProfile['founder_name'],
        'founder_title' => isset($_POST['founder_title']) ? validate_input($_POST['founder_title']) : $existingProfile['founder_title'],
        'company_name' => isset($_POST['company_name']) ? validate_input($_POST['company_name']) : $existingProfile['company_name'],
        'company_website' => isset($_POST['company_website']) ? validate_input($_POST['company_website']) : $existingProfile['company_website'],
        'company_industry' => isset($_POST['company_industry']) ? validate_input($_POST['company_industry']) : $existingProfile['company_industry'],
        'company_description' => isset($_POST['company_description']) ? validate_input($_POST['company_description']) : $existingProfile['company_description'],
        'target_audience' => isset($_POST['target_audience']) ? validate_input($_POST['target_audience']) : $existingProfile['target_audience'],
        'brand_voice' => isset($_POST['brand_voice']) ? validate_input($_POST['brand_voice']) : $existingProfile['brand_voice'],
        'content_goals' => isset($_POST['content_goals']) ? validate_input($_POST['content_goals']) : $existingProfile['content_goals'],
        'key_products' => isset($_POST['key_products']) ? validate_input($_POST['key_products']) : $existingProfile['key_products'],
        'differentiators' => isset($_POST['differentiators']) ? validate_input($_POST['differentiators']) : $existingProfile['differentiators'],
        'ideal_customer_profile' => isset($_POST['ideal_customer_profile']) ? validate_input($_POST['ideal_customer_profile']) : $existingProfile['ideal_customer_profile'],
        'top_problems_solved' => isset($_POST['top_problems_solved']) ? social_media_normalize_list($_POST['top_problems_solved']) : $existingProfile['top_problems_solved'],
        'unique_selling_points' => isset($_POST['unique_selling_points']) ? social_media_normalize_list($_POST['unique_selling_points']) : $existingProfile['unique_selling_points'],
        'instagram_handle' => isset($_POST['instagram_handle']) ? validate_input($_POST['instagram_handle']) : $existingProfile['instagram_handle'],
        'brand_colors' => isset($_POST['brand_colors']) ? social_media_normalize_color_list($_POST['brand_colors']) : $existingProfile['brand_colors'],
        'visual_mood' => isset($_POST['visual_mood']) ? social_media_normalize_list($_POST['visual_mood']) : $existingProfile['visual_mood'],
        'tone_attributes' => isset($_POST['tone_attributes']) ? social_media_normalize_list($_POST['tone_attributes']) : $existingProfile['tone_attributes'],
        'reference_brands' => isset($_POST['reference_brands']) ? social_media_normalize_list($_POST['reference_brands']) : $existingProfile['reference_brands'],
        'competitors' => isset($_POST['competitors']) ? social_media_normalize_list($_POST['competitors']) : $existingProfile['competitors'],
        'competitor_notes' => isset($_POST['competitor_notes']) ? validate_input($_POST['competitor_notes']) : $existingProfile['competitor_notes'],
        'website_snapshot' => $existingProfile['website_snapshot'],
        'website_extracted_at' => $existingProfile['website_extracted_at'],
        'moodboard_images' => $existingProfile['moodboard_images'],
    ];

    if (!empty($_POST['website_snapshot_json'])) {
        $snapshot = json_decode($_POST['website_snapshot_json'], true);
        if (is_array($snapshot)) {
            $profileData['website_snapshot'] = $snapshot;
        }
    }
    if (!empty($_POST['website_extracted_at'])) {
        $profileData['website_extracted_at'] = validate_input($_POST['website_extracted_at']);
    }
    if (!empty($_POST['existing_moodboard_images'])) {
        $profileData['moodboard_images'] = social_media_normalize_list($_POST['existing_moodboard_images']);
    }

    return $profileData;
}


function submitBlogComment()
{
    global $config, $lang;
    $comment_error = $name = $email = $user_id = $comment = null;
    $result = array();
    $is_admin = '0';
    $is_login = false;
    if (checkloggedin()) {
        $is_login = true;
    }
    $avatar = $config['site_url'] . 'storage/profile/default_user.png';
    if (!($is_login || isset($_SESSION['admin']['id']))) {
        if (empty($_POST['user_name']) || empty($_POST['user_email'])) {
            $comment_error = __('All fields are required.');
        } else {
            $name = validate_input($_POST['user_name']);
            $email = validate_input($_POST['user_email']);

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if (!preg_match($regex, $email)) {
                $comment_error = __('This is not a valid email address.');
            }
        }
    } else if ($is_login && isset($_SESSION['admin']['id'])) {
        $commenting_as = 'admin';
        if (!empty($_POST['commenting-as'])) {
            if (in_array($_POST['commenting-as'], array('admin', 'user'))) {
                $commenting_as = $_POST['commenting-as'];
            }
        }
        if ($commenting_as == 'admin') {
            $is_admin = '1';
            $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
            $user_id = $_SESSION['admin']['id'];
            $name = $info['name'];
            $email = $info['email'];
            if (!empty($info['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
            }
        } else {
            $user_id = $_SESSION['user']['id'];
            $user_data = get_user_data(null, $user_id);
            $name = $user_data['name'];
            $email = $user_data['email'];
            if (!empty($user_data['image'])) {
                $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
            }
        }
    } else if ($is_login) {
        $user_id = $_SESSION['user']['id'];
        $user_data = get_user_data(null, $user_id);
        $name = $user_data['name'];
        $email = $user_data['email'];
        if (!empty($user_data['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $user_data['image'];
        }
    } else if (isset($_SESSION['admin']['id'])) {
        $is_admin = '1';
        $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
        $user_id = $_SESSION['admin']['id'];
        $name = $info['name'];
        $email = $info['email'];
        if (!empty($info['image'])) {
            $avatar = $config['site_url'] . 'storage/profile/' . $info['image'];
        }
    } else {
        $comment_error = __('Please login to post a comment.');
    }

    if (empty($_POST['comment'])) {
        $comment_error = __('All fields are required.');
    } else {
        $comment = validate_input($_POST['comment']);
    }

    $duplicates = ORM::for_table($config['db']['pre'] . 'blog_comment')
        ->where('blog_id', $_POST['comment_post_ID'])
        ->where('name', $name)
        ->where('email', $email)
        ->where('comment', $comment)
        ->count();

    if ($duplicates > 0) {
        $comment_error = __('Duplicate Comment: This comment is already exists.');
    }

    if (!$comment_error) {
        if ($is_admin) {
            $approve = '1';
        } else {
            if ($config['blog_comment_approval'] == 1) {
                $approve = '0';
            } else if ($config['blog_comment_approval'] == 2) {
                if ($is_login) {
                    $approve = '1';
                } else {
                    $approve = '0';
                }
            } else {
                $approve = '1';
            }
        }

        $blog_cmnt = ORM::for_table($config['db']['pre'] . 'blog_comment')->create();
        $blog_cmnt->blog_id = $_POST['comment_post_ID'];
        $blog_cmnt->user_id = $user_id;
        $blog_cmnt->is_admin = $is_admin;
        $blog_cmnt->name = $name;
        $blog_cmnt->email = $email;
        $blog_cmnt->comment = $comment;
        $blog_cmnt->created_at = date('Y-m-d H:i:s');
        $blog_cmnt->active = $approve;
        $blog_cmnt->parent = $_POST['comment_parent'];
        $blog_cmnt->save();

        $id = $blog_cmnt->id();
        $date = date('d, M Y');
        $approve_txt = '';
        if ($approve == '0') {
            $approve_txt = '<em><small>' . __('Comment is posted, wait for the reviewer to approve.') . '</small></em>';
        }

        $html = '<li id="li-comment-' . $id . '"';
        if ($_POST['comment_parent'] != 0) {
            $html .= 'class="children-2"';
        }
        $html .= '>
                   <div class="comments-box" id="comment-' . $id . '">
                        <div class="comments-avatar">
                            <img src="' . $avatar . '" alt="' . $name . '">
                        </div>
                        <div class="comments-text">
                            <div class="avatar-name">
                                <h5>' . $name . '</h5>
                                <span>' . $date . '</span>
                            </div>
                            ' . $approve_txt . '
                            <p>' . nl2br(stripcslashes($comment)) . '</p>
                        </div>
                    </div>
                </li>';

        $result['success'] = true;
        $result['html'] = $html;
        $result['id'] = $id;
    } else {
        $result['success'] = false;
        $result['error'] = $comment_error;
    }
    die(json_encode($result));
}

function generate_content()
{
    $result = array();
    global $config;

    @ini_set('memory_limit', '256M');
    @ini_set('output_buffering', 'on');
    session_write_close(); // make session read-only

    // disable default disconnect checks
    ignore_user_abort(true);

    //Disable time limit
    @set_time_limit(0);

    /* Try to increase the database timeout as well */
    ORM::raw_execute("set session wait_timeout=600;");

    //Initialize the output buffer
    if(function_exists('apache_setenv')){
        @apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    while (ob_get_level() != 0) {
        ob_end_flush();
    }
    ob_implicit_flush(1);
    ob_start();

    // connection_aborted() use this

    header('Content-type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');
    header("Content-Encoding: none");

    // if disabled by admin
    if (!get_option("enable_ai_templates", 1)) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die('data: '. json_encode($result).PHP_EOL);
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die('data: '. json_encode($result).PHP_EOL);
            }
        }

        $_POST = validate_input($_GET);

        if (!empty($_POST['ai_template'])) {

            $prompt = '';
            $text = array();
            $max_tokens = (int)$_POST['max_results'];
            $max_results = (int)$_POST['no_of_results'];
            $temperature = (float)$_POST['quality'];

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $words_limit = $membership['settings']['ai_words_limit'];
            $plan_templates = $membership['settings']['ai_templates'];

            if (get_option('single_model_for_plans'))
                $model = get_option('open_ai_model', get_default_openai_model());
            else
                $model = $membership['settings']['ai_model'];
            $model = normalize_openai_model($model);


            $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

            // check if user's membership have the template
            if (!in_array($_POST['ai_template'], $plan_templates)) {
                $result['success'] = false;
                $result['error'] = __('Upgrade your membership plan to use this template');
                die('data: '. json_encode($result).PHP_EOL);
            }

            if ($words_limit != -1){
                $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

                // check user's word limit
                if ($total_words_available < 50) {
                    $result['success'] = false;
                    $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                    die('data: '. json_encode($result).PHP_EOL);
                }

                if($total_words_available < $max_tokens){
                    $max_tokens = $total_words_available;
                }
            }

            switch ($_POST['ai_template']) {
                case 'blog-ideas':
                    if (!empty($_POST['description'])) {
                        $prompt = create_blog_idea_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'blog-intros':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_intros_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'blog-titles':
                    if (!empty($_POST['description'])) {
                        $prompt = create_blog_titles_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'blog-section':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_section_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'blog-conclusion':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_blog_conclusion_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'article-writer':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_article_writer_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'article-rewriter':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_article_rewriter_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'article-outlines':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_article_outlines_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'talking-points':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_talking_points_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'paragraph-writer':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_paragraph_writer_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'content-rephrase':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_content_rephrase_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'facebook-ads':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_facebook_ads_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'facebook-ads-headlines':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_facebook_ads_headlines_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'google-ad-titles':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_google_ads_titles_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'google-ad-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_google_ads_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'linkedin-ad-headlines':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_linkedin_ads_headlines_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'linkedin-ad-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_linkedin_ads_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'app-and-sms-notifications':
                    if (!empty($_POST['description'])) {
                        $prompt = create_app_sms_notifications_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'text-extender':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_text_extender_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'content-shorten':
                    if (!empty($_POST['description'])) {
                        $prompt = create_content_shorten_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'quora-answers':
                    if (!empty($_POST['title']) && !empty($_POST['description'])) {
                        $prompt = create_quora_answers_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'summarize-for-2nd-grader':
                    if (!empty($_POST['description'])) {
                        $prompt = create_summarize_2nd_grader_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'stories':
                    if (!empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_stories_prompt($_POST['audience'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'bullet-point-answers':
                    if (!empty($_POST['description'])) {
                        $prompt = create_bullet_point_answers_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'definition':
                    if (!empty($_POST['keyword'])) {
                        $prompt = create_definition_prompt($_POST['keyword'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'answers':
                    if (!empty($_POST['description'])) {
                        $prompt = create_answers_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'questions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_questions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'passive-active-voice':
                    if (!empty($_POST['description'])) {
                        $prompt = create_passive_active_voice_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'pros-cons':
                    if (!empty($_POST['description'])) {
                        $prompt = create_pros_cons_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'rewrite-with-keywords':
                    if (!empty($_POST['description']) && !empty($_POST['keywords'])) {
                        $prompt = create_rewrite_with_keywords_prompt($_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'emails':
                    if (!empty($_POST['recipient']) && !empty($_POST['recipient-position']) && !empty($_POST['description'])) {
                        $prompt = create_emails_prompt($_POST['recipient'], $_POST['recipient-position'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'emails-v2':
                    if (!empty($_POST['from']) && !empty($_POST['to']) && !empty($_POST['goal']) && !empty($_POST['description'])) {
                        $prompt = create_emails_v2_prompt($_POST['from'], $_POST['to'], $_POST['goal'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'email-subject-lines':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_email_subject_lines_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'startup-name-generator':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_startup_name_generator_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'company-bios':
                    if (!empty($_POST['description']) && !empty($_POST['title']) && !empty($_POST['platform'])) {
                        $prompt = create_company_bios_prompt($_POST['title'], $_POST['description'], $_POST['platform'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'company-mission':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_company_mission_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'company-vision':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_company_vision_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'product-name-generator':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_product_name_generator_prompt($_POST['description'], $_POST['title'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'product-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_product_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'amazon-product-titles':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_titles_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'amazon-product-descriptions':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_descriptions_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'amazon-product-features':
                    if (!empty($_POST['title']) && !empty($_POST['audience']) && !empty($_POST['description'])) {
                        $prompt = create_amazon_product_features_prompt($_POST['title'], $_POST['description'], $_POST['audience'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'social-post-personal':
                    if (!empty($_POST['description'])) {
                        $prompt = create_social_post_personal_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'social-post-business':
                    if (!empty($_POST['title']) && !empty($_POST['information']) && !empty($_POST['description'])) {
                        $prompt = create_social_post_business_prompt($_POST['title'], $_POST['information'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'instagram-captions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_instagram_captions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'instagram-hashtags':
                    if (!empty($_POST['description'])) {
                        $prompt = create_instagram_hashtags_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'twitter-tweets':
                    if (!empty($_POST['description'])) {
                        $prompt = create_twitter_tweets_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'youtube-titles':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_titles_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'youtube-descriptions':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_descriptions_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'youtube-outlines':
                    if (!empty($_POST['description'])) {
                        $prompt = create_youtube_outlines_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                case 'linkedin-posts':
                    if (!empty($_POST['description'])) {
                        $prompt = create_linkedin_posts_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'tiktok-video-scripts':
                    if (!empty($_POST['description'])) {
                        $prompt = create_tiktok_video_scripts_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'meta-tags-blog':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
                        $prompt = create_meta_tags_blog_prompt($_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'meta-tags-homepage':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
                        $prompt = create_meta_tags_homepage_prompt($_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'meta-tags-product':
                    if (!empty($_POST['title']) && !empty($_POST['keywords']) && !empty($_POST['description']) && !empty($_POST['company_name'])) {
                        $prompt = create_meta_tags_product_prompt($_POST['company_name'], $_POST['title'], $_POST['description'], $_POST['keywords'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'tone-changer':
                    if (!empty($_POST['description'])) {
                        $prompt = create_tone_changer_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'song-lyrics':
                    if (!empty($_POST['genre']) && !empty($_POST['title'])) {
                        $prompt = create_song_lyrics_prompt($_POST['title'], $_POST['genre'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'translate':
                    if (!empty($_POST['description'])) {
                        $prompt = create_translate_prompt($_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'faqs':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_faqs_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'faq-answers':
                    if (!empty($_POST['description']) && !empty($_POST['title']) && !empty($_POST['question'])) {
                        $prompt = create_faq_answers_prompt($_POST['title'], $_POST['description'], $_POST['question'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                case 'testimonials-reviews':
                    if (!empty($_POST['description']) && !empty($_POST['title'])) {
                        $prompt = create_testimonials_reviews_prompt($_POST['title'], $_POST['description'], $_POST['language'], $_POST['tone']);
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('All fields with (*) are required.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }
                    break;
                default:
                    // check for custom template
                    $ai_template = ORM::for_table($config['db']['pre'] . 'ai_custom_templates')
                        ->where('active', '1')
                        ->where('slug', $_POST['ai_template'])
                        ->find_one();
                    if (!empty($ai_template)) {
                        $prompt = $ai_template['prompt'];

                        if ($_POST['language'] == 'en') {
                            $prompt = $ai_template['prompt'];
                        } else {
                            $languages = get_ai_languages();
                            $prompt = "Provide response in " . $languages[$_POST['language']] . ".\n\n " . $ai_template['prompt'];
                        }

                        if (!empty($ai_template['parameters'])) {
                            $parameters = json_decode($ai_template['parameters'], true);
                            foreach ($parameters as $key => $parameter) {
                                if (!empty($_POST['parameter'][$key])) {
                                    if (strpos($prompt, '{{' . $parameter['title'] . '}}') !== false) {
                                        $prompt = str_replace('{{' . $parameter['title'] . '}}', $_POST['parameter'][$key], $prompt);
                                    } else {
                                        if(!empty($_POST['parameter'][$key])) {
                                            $prompt .= "\n\n " . $parameter['title'] . ": " . $_POST['parameter'][$key];
                                        }
                                    }
                                }
                            }
                        }

                        $prompt .= " \n\n Voice of tone of the response must be " . $_POST['tone'] . '.';
                    } else {
                        $result['success'] = false;
                        $result['error'] = __('Unexpected error, please try again.');
                        die('data: '. json_encode($result).PHP_EOL);
                    }

                    break;
            }

            if (function_exists('hatchers_enrich_prompt_with_intelligence')) {
                $prompt = hatchers_enrich_prompt_with_intelligence(
                    $prompt,
                    $_SESSION['user']['id'],
                    'ai template ' . $_POST['ai_template'],
                    'atlas'
                );
            }

            // check bad words
            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die('data: '. json_encode($result).PHP_EOL);
            }

            $used_tokens = 0;
            $text = array();

            require_once ROOTPATH . '/includes/lib/Tokenizer-GPT3/autoload.php';
            $tokenizer = new \Ze\TokenizerGpt3\Gpt3Tokenizer(new \Ze\TokenizerGpt3\Gpt3TokenizerConfig());

            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            if (array_key_exists($model, get_opeai_chat_models())) {

                $messages = [];
                if($max_results > 1) {
                    $messages[] = [
                        "role" => "system",
                        "content" => "You have to generate $max_results different results from the user's prompt everytime."
                    ];
                }

                $messages[] = [
                        "role" => "user",
                        "content" => $prompt
                    ];

                $complete = $open_ai->chat([
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => $temperature,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'max_tokens' => $max_tokens,
                    'user' => $_SESSION['user']['id'],
                    'stream' => true
                ],
                    function ($curl_info, $data) use (&$text, $model) {
                        if ($obj = json_decode($data) and $obj->error->code != "") {
                            $result = [];
                            $result['api_error'] = $obj->error->message;
                            $result['error'] = get_api_error_message( curl_getinfo($curl_info, CURLINFO_HTTP_CODE));
                            echo $data = 'data: '. json_encode($result).PHP_EOL;
                        } else {
                            echo $data;

                            $array = explode('data: ', $data);
                            foreach ($array as $ar){
                                $ar = json_decode($ar, true);

                                if(isset($ar["choices"])) {
                                    if (array_key_exists($model, get_opeai_chat_models())) {
                                        if (count($ar['choices']) > 1) {
                                            foreach ($ar['choices'] as $value) {
                                                $text[] = $value['delta']['content'] . "\n\n";
                                            }
                                        } else {
                                            $text[] = $ar['choices'][0]['delta']['content'];
                                        }
                                    } else {
                                        if (count($ar['choices']) > 1) {
                                            foreach ($ar['choices'] as $value) {
                                                $text[] = $value['text'] . "\n\n";
                                            }
                                        } else {
                                            $text[] = $ar['choices'][0]['text'];
                                        }
                                    }
                                }
                            }
                        }

                        echo PHP_EOL;
                        while(ob_get_level() > 0) {
                            ob_end_flush();
                        }
                        ob_flush();
                        flush();
                        return strlen($data);
                    });
            }

            if (!empty($text)) {

                $ai_message = implode("\n\n\n\n", $text);

                $used_tokens += $tokenizer->count($ai_message);
                /* GPT 4 uses more tokens */
                if (strpos($model, 'gpt-4') === 0) {
                    $used_tokens *= ceil(1.1);
                }

                $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
                $word_used->user_id = $_SESSION['user']['id'];
                $word_used->words = $used_tokens;
                $word_used->date = date('Y-m-d H:i:s');
                $word_used->save();

                update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $used_tokens);

            }
        }
    }
}

function generate_image()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        // if disabled by admin
        if (!$config['enable_ai_images']) {
            $result['success'] = false;
            $result['error'] = __('This feature is disabled by the admin.');
            die(json_encode($result));
        }

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['campaign_type'])) {

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $images_limit = $membership['settings']['ai_images_limit'];

            $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);
            $postsToGenerate = 9;

            // check user's images limit
            if ($images_limit != -1 && ((($images_limit + get_user_option($_SESSION['user']['id'], 'total_images_available', 0)) - $total_images_used) < $postsToGenerate)) {
                $result['success'] = false;
                $result['error'] = __('Images limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            $profile = social_media_get_profile($_SESSION['user']['id']);
            if (empty($profile['company_name']) || empty($profile['company_description'])) {
                $result['success'] = false;
                $result['error'] = __('Complete your company profile first from Account Settings to generate social media posts.');
                die(json_encode($result));
            }

            $prompt = social_media_build_campaign_brief($_POST);

            // check bad words
            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            $ideas = social_media_generate_batch($_SESSION['user']['id'], $prompt);
            $posts = social_media_store_generated_posts($_SESSION['user']['id'], $ideas, $prompt);

            $image_used = ORM::for_table($config['db']['pre'] . 'image_used')->create();
            $image_used->user_id = $_SESSION['user']['id'];
            $image_used->images = $postsToGenerate;
            $image_used->date = date('Y-m-d H:i:s');
            $image_used->save();

            update_user_option($_SESSION['user']['id'], 'total_images_used', $total_images_used + $postsToGenerate);

            $result['success'] = true;
            $result['posts'] = $posts;
            $result['description'] = !empty($_POST['description']) ? $_POST['description'] : '';
            $result['old_used_images'] = (int) $total_images_used;
            $result['current_used_images'] = (int) $total_images_used + $postsToGenerate;

            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function generate_instagram_grid()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        if (!$config['enable_ai_images']) {
            $result['success'] = false;
            $result['error'] = __('This feature is disabled by the admin.');
            die(json_encode($result));
        }

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);
        $_POST = validate_input($_POST);

        if (!empty($_POST['campaign_type'])) {
            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $images_limit = $membership['settings']['ai_images_limit'];
            $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);
            $postsToGenerate = 9;

            if ($images_limit != -1 && ((($images_limit + get_user_option($_SESSION['user']['id'], 'total_images_available', 0)) - $total_images_used) < $postsToGenerate)) {
                $result['success'] = false;
                $result['error'] = __('Images limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            $profile = social_media_get_profile($_SESSION['user']['id']);
            if (empty($profile['company_name']) || empty($profile['company_description'])) {
                $result['success'] = false;
                $result['error'] = __('Complete your company profile first from Account Settings to generate an Instagram grid.');
                die(json_encode($result));
            }

            $prompt = social_media_build_campaign_brief($_POST);

            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            $gridBatch = social_media_generate_instagram_grid($_SESSION['user']['id'], $prompt, $_POST);
            $batchKey = uniqid('grid_');
            $posts = social_media_store_generated_posts($_SESSION['user']['id'], $gridBatch['items'], $prompt, [
                'batch_key' => $batchKey
            ]);

            $image_used = ORM::for_table($config['db']['pre'] . 'image_used')->create();
            $image_used->user_id = $_SESSION['user']['id'];
            $image_used->images = $postsToGenerate;
            $image_used->date = date('Y-m-d H:i:s');
            $image_used->save();

            update_user_option($_SESSION['user']['id'], 'total_images_used', $total_images_used + $postsToGenerate);

            $result['success'] = true;
            $result['posts'] = $posts;
            $result['grid_template'] = !empty($gridBatch['template']) ? $gridBatch['template'] : [];
            $result['grid_template_key'] = !empty($gridBatch['template_key']) ? $gridBatch['template_key'] : '';
            $result['company_profile'] = [
                'company_name' => !empty($profile['company_name']) ? $profile['company_name'] : '',
                'instagram_handle' => !empty($profile['instagram_handle']) ? $profile['instagram_handle'] : '',
                'company_description' => !empty($profile['company_description']) ? $profile['company_description'] : '',
                'company_website' => !empty($profile['company_website']) ? $profile['company_website'] : '',
                'company_logo' => !empty($profile['company_logo']) ? $config['site_url'] . 'storage/company/' . $profile['company_logo'] : '',
            ];
            $result['old_used_images'] = (int) $total_images_used;
            $result['current_used_images'] = (int) $total_images_used + $postsToGenerate;

            die(json_encode($result));
        }
    }

    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function save_document()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $content = validate_input($_POST['content'], true);
        $_POST = validate_input($_POST);
        $_POST['content'] = $content;

        if (!empty($_POST['id'])) {
            $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->find_one($_POST['id']);
        } else {
            $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
        }

        $content->user_id = $_SESSION['user']['id'];
        $content->title = $_POST['title'];

        if (!empty($_POST['content']))
            $content->content = $_POST['content'];

        $content->template = $_POST['ai_template'];
        $content->created_at = date('Y-m-d H:i:s');
        $content->save();

        $result['success'] = true;
        $result['id'] = $content->id();
        $result['message'] = __('Successfully Saved.');
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_document()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $data = ORM::for_table($config['db']['pre'] . 'ai_documents')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();

        if ($data) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function regenerate_social_post()
{
    $result = array();
    if (checkloggedin()) {
        $_POST = validate_input($_POST);
        $postId = !empty($_POST['id']) ? (int) $_POST['id'] : 0;
        $voteValue = isset($_POST['vote']) ? (int) $_POST['vote'] : 0;

        if ($postId <= 0) {
            $result['success'] = false;
            $result['error'] = __('Post not found.');
            die(json_encode($result));
        }

        if ($voteValue === -1 || $voteValue === 1) {
            social_media_record_post_feedback($_SESSION['user']['id'], $postId, $voteValue, [
                'source' => 'regenerate_flow',
            ]);
        }

        $regenerated = social_media_regenerate_post($_SESSION['user']['id'], $postId);
        if (!$regenerated['success']) {
            $result['success'] = false;
            $result['error'] = $regenerated['error'];
            die(json_encode($result));
        }

        $result['success'] = true;
        $result['message'] = __('Image regenerated successfully.');
        $result['post'] = $regenerated['post'];
        die(json_encode($result));
    }

    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function vote_social_post_image()
{
    $result = array();
    if (checkloggedin()) {
        $_POST = validate_input($_POST);
        $postId = !empty($_POST['id']) ? (int) $_POST['id'] : 0;
        $voteValue = isset($_POST['vote']) ? (int) $_POST['vote'] : 0;

        if ($postId <= 0 || !in_array($voteValue, [-1, 1], true)) {
            $result['success'] = false;
            $result['error'] = __('Invalid vote request.');
            die(json_encode($result));
        }

        if (!social_media_record_post_feedback($_SESSION['user']['id'], $postId, $voteValue, [
            'source' => 'manual_vote',
        ])) {
            $result['success'] = false;
            $result['error'] = __('Unable to save your feedback right now.');
            die(json_encode($result));
        }

        $post = social_media_get_post($_SESSION['user']['id'], $postId);
        $result['success'] = true;
        $result['message'] = $voteValue > 0
            ? __('Thanks, we will learn from this image.')
            : __('Thanks, we will use this feedback next time.');
        $result['post'] = $post;
        die(json_encode($result));
    }

    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function save_social_post_overlay()
{
    $result = array();
    if (checkloggedin()) {
        $overlay = isset($_POST['overlay_text']) ? trim($_POST['overlay_text']) : '';
        $_POST = validate_input($_POST);
        $postId = !empty($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($postId <= 0) {
            $result['success'] = false;
            $result['error'] = __('Post not found.');
            die(json_encode($result));
        }

        $saved = social_media_update_post_overlay($_SESSION['user']['id'], $postId, $overlay);
        if (!$saved['success']) {
            $result['success'] = false;
            $result['error'] = $saved['error'];
            die(json_encode($result));
        }

        $result['success'] = true;
        $result['message'] = __('Overlay text updated.');
        $result['post'] = $saved['post'];
        die(json_encode($result));
    }

    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_image()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        if (social_media_delete_post($_SESSION['user']['id'], $_POST['id'])) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }

        $images = ORM::for_table($config['db']['pre'] . 'ai_images')
            ->select('image')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ));

        foreach ($images->find_array() as $row) {
            $image_dir = "../storage/ai_images/";
            $main_image = trim((string) $row['image']);
            // delete Image
            if (!empty($main_image)) {
                $file = $image_dir . $main_image;
                if (file_exists($file))
                    unlink($file);

                $file = $image_dir . 'small_'.$main_image;
                if (file_exists($file))
                    unlink($file);
            }
        }

        if ($images->delete_many()) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function load_ai_chats()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (empty($_POST['conv_id'])) {
            $result['success'] = false;
            $result['error'] = __('Unexpected error, please try again.');
            die(json_encode($result));
        }

        /* load chats */
        $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
            ->where('user_id', $_SESSION['user']['id']);

        if ($_POST['conv_id'] == 'default') {
            $data->where_null('conversation_id');

            if (!empty($_POST['bot_id']))
                $data->where('bot_id', $_POST['bot_id']);
            else
                $data->where_null('bot_id');

        } else {
            $data->where('conversation_id', $_POST['conv_id']);
        }

        $chats = $data->find_array();

        // format date
        foreach ($chats as &$chat) {
            $chat['date_formatted'] = date('F d, Y', strtotime($chat['date']));
        }

        $result['success'] = true;
        $result['chats'] = $chats;
        die(json_encode($result));
    }
}

function edit_conversation_title()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        $_POST = validate_input($_POST);

        if (!empty($_POST['id'])) {
            $conversations = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one($_POST['id']);
            $conversations->set('title', $_POST['title']);

            $conversations->save();
        }

        $result['success'] = true;
        die(json_encode($result));
    }
}

function send_ai_message()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        $temp_msg = validate_input($_POST['msg'], true);
        $_POST = validate_input($_POST);
        $_POST['msg'] = $temp_msg;

        set_time_limit(0);

        $membership = get_user_membership_detail($_SESSION['user']['id']);
        $words_limit = $membership['settings']['ai_words_limit'];
        $plan_ai_chat = $membership['settings']['ai_chat'];
        $membership_ai_chatbots = !empty($membership['settings']['ai_chatbots']) ? $membership['settings']['ai_chatbots'] : [];

        if (!$plan_ai_chat || ($_POST['bot_id'] != null && !in_array($_POST['bot_id'], $membership_ai_chatbots))) {
            $result['success'] = false;
            $result['error'] = __('Upgrade your membership plan to use this feature.');
            die(json_encode($result));
        }

        $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);


        $max_tokens = (int)get_option("ai_chat_max_token", '-1');
        if ($words_limit != -1){
            $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

            // check user's word limit
            if ($total_words_available < 50) {
                $result['success'] = false;
                $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            if($total_words_available < $max_tokens){
                $max_tokens = $total_words_available;
            }
        }

        /* check bad words */
        if ($word = check_bad_words($_POST['msg'])) {
            $result['success'] = false;
            $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
            die(json_encode($result));
        }

        $conversation_id = null;
        if (empty($_POST['conv_id']) || (!empty($_POST['conv_id']) && $_POST['conv_id'] == 'default')) {
            $conversations = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')->create();
            $conversations->title = __('New Conversation');
            $conversations->user_id = $_SESSION['user']['id'];
            $conversations->last_message = '...';
            $conversations->updated_at = date('Y-m-d H:i:s');

            if (!empty($_POST['bot_id']))
                $conversations->bot_id = $_POST['bot_id'];

            $conversations->save();

            $conversation_id = $conversations->id();

            if (!empty($_POST['conv_id']) && $_POST['conv_id'] == 'default') {
                $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where_null('conversation_id');

                if (!empty($_POST['bot_id']))
                    $data->where('bot_id', $_POST['bot_id']);
                else
                    $data->where_null('bot_id');

                $chats = $data->find_result_set();

                $chats->set('conversation_id', $conversation_id);
                $chats->save();
            }
        } else {
            $conversation_id = $_POST['conv_id'];
        }

        /* save user message */
        $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')->create();
        $chat->user_id = $_SESSION['user']['id'];
        $chat->user_message = $_POST['msg'];
        $chat->conversation_id = $conversation_id;
        $chat->date = date('Y-m-d H:i:s');

        if (!empty($_POST['bot_id']))
            $chat->bot_id = $_POST['bot_id'];

        $chat->save();

        $result['success'] = true;
        $result['conversation_id'] = $conversation_id;
        $result['last_message_id'] = $chat->id();
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function hatchers_stream_inline_chat_response($text)
{
    $text = trim((string) $text);
    if ($text === '') {
        $text = "I'm Atlas. I can help with guidance and confirmed write actions across Hatchers.";
    }

    $chunks = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    foreach ($chunks as $chunk) {
        $payload = [
            'choices' => [
                [
                    'delta' => [
                        'content' => $chunk,
                    ],
                ],
            ],
        ];
        echo 'data: ' . json_encode($payload) . PHP_EOL . PHP_EOL;
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_flush();
        flush();
    }

    echo 'data: [DONE]' . PHP_EOL . PHP_EOL;
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    ob_flush();
    flush();
}

function hatchers_save_inline_chat_response($userId, $conversationId, $lastMessageId, $aiMessage)
{
    global $config;

    $aiMessage = trim((string) $aiMessage);
    if ($aiMessage === '') {
        return;
    }

    $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where('user_id', $userId)
        ->find_one($lastMessageId);

    if (empty($chat)) {
        return;
    }

    $chat->set('ai_message', $aiMessage);
    $chat->set('date', date('Y-m-d H:i:s'));
    $chat->save();

    $updateConversation = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
        ->find_one($conversationId);

    if (!empty($updateConversation)) {
        $updateConversation->set('updated_at', date('Y-m-d H:i:s'));
        $updateConversation->set('last_message', strlimiter(strip_tags($aiMessage), 100));
        $updateConversation->save();
    }

    $usedTokens = max(1, str_word_count(strip_tags($aiMessage)));
    $wordUsed = ORM::for_table($config['db']['pre'] . 'word_used')->create();
    $wordUsed->user_id = $userId;
    $wordUsed->words = $usedTokens;
    $wordUsed->date = date('Y-m-d H:i:s');
    $wordUsed->save();

    $totalWordsUsed = (int) get_user_option($userId, 'total_words_used', 0);
    update_user_option($userId, 'total_words_used', $totalWordsUsed + $usedTokens);
}

function chat_stream()
{
    $result = array();
    global $config;

    @ini_set('memory_limit', '256M');
    @ini_set('output_buffering', 'on');
    session_write_close(); // make session read-only

    // disable default disconnect checks
    ignore_user_abort(true);

    //Disable time limit
    @set_time_limit(0);

    /* Try to increase the database timeout as well */
    ORM::raw_execute("set session wait_timeout=600;");

    //Initialize the output buffer
    if(function_exists('apache_setenv')){
        @apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    while (ob_get_level() != 0) {
        ob_end_flush();
    }
    ob_implicit_flush(1);
    ob_start();

    // connection_aborted() use this

    header('Content-type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');
    header("Content-Encoding: none");

    // if disabled by admin
    if (!$config['enable_ai_chat']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die('data: '. json_encode($result).PHP_EOL);
    }

    if (checkloggedin()) {
        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die('data: '. json_encode($result).PHP_EOL);
            }
        }

        $membership = get_user_membership_detail($_SESSION['user']['id']);
        $words_limit = $membership['settings']['ai_words_limit'];
        $plan_ai_chat = $membership['settings']['ai_chat'];

        if (!$plan_ai_chat) {
            $result['success'] = false;
            $result['error'] = __('Upgrade your membership plan to use this feature.');
            die('data: '. json_encode($result).PHP_EOL);
        }

        if (get_option('single_model_for_plans'))
            $model = get_option('open_ai_chat_model', get_default_openai_chat_model());
        else
            $model = $membership['settings']['ai_chat_model'];

        $model = normalize_openai_model(!empty($model) ? $model : get_default_openai_chat_model());

        $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

        $total_available_words = $words_limit - $total_words_used;

        $max_tokens = (int)get_option("ai_chat_max_token", '-1');
        // check user's word limit
        $max_tokens_limit = $max_tokens == -1 ? 100 : $max_tokens;
        if ($words_limit != -1){
            $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

            // check user's word limit
            if ($total_words_available < 50) {
                $result['success'] = false;
                $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                die('data: '. json_encode($result).PHP_EOL);
            }

            if($total_words_available < $max_tokens){
                $max_tokens = $total_words_available;
            }
        }

        if(is_numeric($_GET['conv_id'])) {
            $conversation_id = (int) validate_input($_GET['conv_id']);
        } else{
            $result['success'] = false;
            $result['error'] = __('Unexpected error, please try again.');
            die('data: '. json_encode($result).PHP_EOL);
        }

        /* create message history */
        $ROLE = "role";
        $CONTENT = "content";
        $USER = "user";
        $SYS = "system";
        $ASSISTANT = "assistant";

        $system_prompt = "You are a helpful AI agent for a founder. Always answer using the founder's company context, priorities, and previous history when relevant.";
        $bot_training_data = null;
        if (!empty($_GET['bot_id'])) {
            $bot_sql = "and `bot_id` = {$_GET['bot_id']}";

            $chat_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')
                ->find_one($_GET['bot_id']);

            /* Check bot exist */
            if (empty($chat_bot['id'])) {
                $result['success'] = false;
                $result['error'] = __('Unexpected error, please try again.');
                die('data: '. json_encode($result).PHP_EOL);
            }

            if (!empty($chat_bot['prompt'])) {
                $system_prompt = $chat_bot['prompt'];
            }
            $bot_training_data = $chat_bot['training_data'];
        } else {
            $bot_sql = "and `bot_id` IS NULL";
        }

        $company_context = social_media_get_company_context_text($_SESSION['user']['id']);
        $history_context = social_media_get_recent_chat_context($_SESSION['user']['id'], 12);
        if (!empty($company_context)) {
            $system_prompt .= "\n\nCompany context:\n" . $company_context;
        }
        if (!empty($history_context)) {
            $system_prompt .= "\n\nRecent company history from prior agent conversations:\n" . $history_context;
        }
        $system_prompt .= "\n\nIf the user asks for content, strategy, messaging, or positioning, tailor the answer to their company and competitors instead of giving generic advice.";
        if (function_exists('hatchers_enrich_system_prompt_with_intelligence')) {
            $system_prompt = hatchers_enrich_system_prompt_with_intelligence(
                $system_prompt,
                $_SESSION['user']['id'],
                !empty($chat_bot['name']) ? 'chat bot ' . $chat_bot['name'] : 'atlas ai chat',
                'atlas'
            );
        }

        // get last 5 messages
        $sql = "SELECT * FROM
                (
                 SELECT * FROM " . $config['db']['pre'] . 'ai_chat' . " 
                 WHERE `user_id` = {$_SESSION['user']['id']} 
                 AND `conversation_id` = $conversation_id 
                 $bot_sql ORDER BY id DESC LIMIT 5
                ) AS sub
                ORDER BY id ASC;";
        $chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
            ->raw_query($sql)
            ->find_array();

        $used_tokens = 0;

        require_once ROOTPATH . '/includes/lib/Tokenizer-GPT3/autoload.php';
        $tokenizer = new \Ze\TokenizerGpt3\Gpt3Tokenizer(new \Ze\TokenizerGpt3\Gpt3TokenizerConfig());

        $history[] = [$ROLE => $SYS, $CONTENT => $system_prompt];

        /* Add training data */
        $bot_training_data = str_replace(array("\r", "\n"), '', $bot_training_data) ?? null;
        if ($bot_training_data) {

            $bot_training_data = json_decode($bot_training_data, true);

            foreach ($bot_training_data as $item) {
                $history[] = [$ROLE => $item["role"], $CONTENT => $item["content"]];
            }
        }

        foreach ($chats as $chat) {
            $history[] = [$ROLE => $USER, $CONTENT => $chat['user_message']];
            if (!empty($chat['ai_message'])) {
                $history[] = [$ROLE => $ASSISTANT, $CONTENT => $chat['ai_message']];
            }
        }

        $lastUserMessage = '';
        foreach (array_reverse($chats) as $chat) {
            if (!empty($chat['user_message'])) {
                $lastUserMessage = trim((string) $chat['user_message']);
                break;
            }
        }

        if ($lastUserMessage !== '') {
            $writeAction = hatchers_handle_write_action_message($_SESSION['user']['id'], $lastUserMessage, [
                'role' => '',
                'app' => 'atlas',
            ]);

            if (!empty($writeAction['handled'])) {
                $aiMessage = trim((string) ($writeAction['reply'] ?? ''));
                hatchers_save_inline_chat_response($_SESSION['user']['id'], $conversation_id, $_GET['last_message_id'], $aiMessage);
                hatchers_stream_inline_chat_response($aiMessage);
                ORM::reset_db();
                return;
            }
        }

        require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
        require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

        $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

        $opts = [
            'model' => $model,
            'messages' => $history,
            'temperature' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'user' => $_SESSION['user']['id'],
            'stream' => true
        ];
        if ($max_tokens != -1) {
            $opts['max_tokens'] = $max_tokens;
        }

        ORM::set_db(null);

        $txt = "";
        $complete = $open_ai->chat($opts, function ($curl_info, $data) use (&$txt) {
            if ($obj = json_decode($data) and $obj->error->code != "") {
                $result = [];
                $result['api_error'] = $obj->error->message;
                $result['error'] = get_api_error_message( curl_getinfo($curl_info, CURLINFO_HTTP_CODE));
                echo $data = 'data: '. json_encode($result).PHP_EOL;
            } else {
                echo $data;

                $array = explode('data: ', $data);
                foreach ($array as $ar){
                    $ar = json_decode($ar, true);
                    if(isset($ar["choices"][0]["delta"]["content"])) {
                        $txt .= $ar["choices"][0]["delta"]["content"];
                    }
                }
            }

            echo PHP_EOL;
            while(ob_get_level() > 0) {
                ob_end_flush();
            }
            ob_flush();
            flush();
            return strlen($data);
        });

        $ai_message = $txt;
        if (!empty($ai_message)) {

            // save chat
            $chat = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one($_GET['last_message_id']);

            $chat->set('ai_message', $ai_message);
            $chat->set('date', date('Y-m-d H:i:s'));
            $chat->save();

            /* update conversation */
            $last_message = strlimiter(strip_tags($ai_message), 100);
            $update_conversation = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                ->find_one($conversation_id);
            $update_conversation->set('updated_at', date('Y-m-d H:i:s'));
            $update_conversation->set('last_message', $last_message);
            $update_conversation->save();

            $used_tokens += $tokenizer->count($ai_message);
            /* GPT 4 uses more tokens */
            if (strpos($model, 'gpt-4') === 0) {
                $used_tokens *= ceil(1.1);
            }

            $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
            $word_used->user_id = $_SESSION['user']['id'];
            $word_used->words = $used_tokens;
            $word_used->date = date('Y-m-d H:i:s');
            $word_used->save();

            update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $used_tokens);
        }
    }

    // close connection
    ORM::reset_db();
}

function delete_ai_chats()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        if (!empty($_GET['conv_id'])) {
            /* Delete chats */
            $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->where('user_id', $_SESSION['user']['id']);

            if ($_GET['conv_id'] == 'default')
                $data->where_null('conversation_id');
            else
                $data->where('conversation_id', $_GET['conv_id']);

            if (!empty($_GET['bot_id']))
                $data->where('bot_id', $_GET['bot_id']);

            $data->delete_many();

            /* Delete conversation */
            if ($_GET['conv_id'] != 'default') {
                ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $_GET['conv_id'])
                    ->delete_many();
            }

            if ($data) {
                $result['success'] = true;
                $result['message'] = __('Deleted Successfully');
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function export_ai_chats()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $text = '';

        if (!empty($_GET['conv_id'])) {

            $data = ORM::for_table($config['db']['pre'] . 'ai_chat')
                ->table_alias('c')
                ->select_many_expr('c.*', 'u.name full_name')
                ->where('c.user_id', $_SESSION['user']['id'])
                ->join($config['db']['pre'] . 'user', 'u.id = c.user_id', 'u');

            if ($_GET['conv_id'] == 'default')
                $data->where_null('conversation_id');
            else
                $data->where('conversation_id', $_GET['conv_id']);

            if (!empty($_GET['bot_id'])) {
                $data->where('bot_id', $_GET['bot_id']);

                $chat_bot = ORM::for_table($config['db']['pre'] . 'ai_chat_bots')
                    ->find_one($_GET['bot_id']);

                /* Check bot exist */
                if (empty($chat_bot['id'])) {
                    $result['success'] = false;
                    $result['error'] = __('Unexpected error, please try again.');
                    die(json_encode($result));
                }

                $ai_name = $chat_bot['name'];
            } else {
                $ai_name = get_option('ai_chat_bot_name', __('AI Chat Bot'));
            }

            foreach ($data->find_array() as $chat) {
                // user
                $text .= "[{$chat['date']}] ";
                $text .= $chat['full_name'] . ': ';
                $text .= $chat['user_message'] . "\n\n";

                // ai
                if (!empty($chat['ai_message'])) {
                    $text .= "[{$chat['date']}] ";
                    $text .= $ai_name . ': ';
                    $text .= $chat['ai_message'] . "\n\n";
                }
            }
        }

        $result['success'] = true;
        $result['text'] = $text;
        die(json_encode($result));
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function speech_to_text()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!$config['enable_speech_to_text']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {
        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_FILES['file']['tmp_name'])) {

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $speech_to_text_limit = $membership['settings']['ai_speech_to_text_limit'];
            $speech_text_file_limit = $membership['settings']['ai_speech_to_text_file_limit'];

            $total_speech_used = get_user_option($_SESSION['user']['id'], 'total_speech_used', 0);

            // check user's speech limit
            if ($speech_to_text_limit != -1 && ((($speech_to_text_limit + get_user_option($_SESSION['user']['id'], 'total_speech_available', 0)) - $total_speech_used) < 1)) {
                $result['success'] = false;
                $result['error'] = __('Audio transcription limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            if ($speech_text_file_limit != -1 && ($_FILES['file']['size'] > $speech_text_file_limit * 1024 * 1024)) {
                $result['success'] = false;
                $result['error'] = __('File size limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            // check bad words
            if ($word = check_bad_words($_POST['description'])) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            $tmp_file = $_FILES['file']['tmp_name'];
            $file_name = basename($_FILES['file']['name']);
            $c_file = curl_file_create($tmp_file, $_FILES['file']['type'], $file_name);
            $complete = $open_ai->transcribe([
                "model" => "whisper-1",
                "file" => $c_file,
                "prompt" => $_POST['description'],
                'language' => !empty($_POST['language']) ? $_POST['language'] : null,
                'user' => $_SESSION['user']['id']
            ]);

            $response = json_decode($complete, true);

            if (isset($response['text'])) {
                $response['text'] = nl2br(trim($response['text']));

                $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                $content->user_id = $_SESSION['user']['id'];
                $content->title = !empty($_POST['title']) ? $_POST['title'] : __('Untitled Document');
                $content->content = $response['text'];
                $content->template = 'speech-to-text';
                $content->created_at = date('Y-m-d H:i:s');
                $content->save();

                $speech_used = ORM::for_table($config['db']['pre'] . 'speech_to_text_used')->create();
                $speech_used->user_id = $_SESSION['user']['id'];
                $speech_used->date = date('Y-m-d H:i:s');
                $speech_used->save();

                update_user_option($_SESSION['user']['id'], 'total_speech_used', $total_speech_used + 1);

                $result['success'] = true;
                $result['text'] = $response['text'];
                $result['old_used_speech'] = (int) $speech_to_text_limit;
                $result['current_used_speech'] = (int) $total_speech_used + 1;
            } else {
                // error log default message
                if (!empty($response['error']['message']))
                    error_log('OpenAI: ' . $response['error']['message']);

                $result['success'] = false;
                $result['api_error'] = $response['error']['message'];
                $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                die(json_encode($result));
            }
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function ai_code()
{
    $result = array();

    global $config;

    // if disabled by admin
    if (!$config['enable_ai_code']) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {

            $prompt = $_POST['description'];

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $words_limit = $membership['settings']['ai_words_limit'];
            $plan_ai_code = $membership['settings']['ai_code'];

            if (get_option('single_model_for_plans'))
                $model = get_option('open_ai_model', get_default_openai_model());
            else
                $model = $membership['settings']['ai_model'];
            $model = normalize_openai_model($model);

            $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

            // check if user's membership have the template
            if (!$plan_ai_code) {
                $result['success'] = false;
                $result['error'] = __('Upgrade your membership plan to use this feature');
                die(json_encode($result));
            }

            $max_tokens = (int)get_option("ai_code_max_token", '-1');
            // check user's word limit
            $max_tokens_limit = $max_tokens == -1 ? 100 : $max_tokens;
            if ($words_limit != -1){
                $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

                // check user's word limit
                if ($total_words_available < 50) {
                    $result['success'] = false;
                    $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                    die(json_encode($result));
                }

                if($total_words_available < $max_tokens){
                    $max_tokens = $total_words_available;
                }
            }

            // check bad words
            if ($word = check_bad_words($prompt)) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
            require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

            $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

            if (array_key_exists($model, get_opeai_chat_models())) {
                $opt = [
                    'model' => $model,
                    'messages' => [
                        [
                            "role" => "user",
                            "content" => $prompt
                        ],
                    ],
                    'temperature' => 1,
                    'n' => 1,
                    'user' => $_SESSION['user']['id']
                ];
                if ($max_tokens != -1) {
                    $opt['max_tokens'] = $max_tokens;
                }
                $complete = $open_ai->chat($opt);
            } else {
                $opt = [
                    'model' => $model,
                    'prompt' => $prompt,
                    'temperature' => 1,
                    'n' => 1,
                ];
                if ($max_tokens != -1) {
                    $opt['max_tokens'] = $max_tokens;
                }
                $complete = $open_ai->completion($opt);
            }

            $response = json_decode($complete, true);

            if (isset($response['choices'])) {
                if (array_key_exists($model, get_opeai_chat_models())) {
                    $text = trim($response['choices'][0]['message']['content']);
                } else {
                    $text = trim($response['choices'][0]['text']);
                }

                $tokens = $response['usage']['completion_tokens'];

                $content = ORM::for_table($config['db']['pre'] . 'ai_documents')->create();
                $content->user_id = $_SESSION['user']['id'];
                $content->title = !empty($_POST['title']) ? $_POST['title'] : __('Untitled Document');
                $content->content = $text;
                $content->template = 'ai-code';
                $content->created_at = date('Y-m-d H:i:s');
                $content->save();

                $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
                $word_used->user_id = $_SESSION['user']['id'];
                $word_used->words = $tokens;
                $word_used->date = date('Y-m-d H:i:s');
                $word_used->save();

                update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $tokens);

                $result['success'] = true;
                $result['text'] = filter_ai_response($text);
                $result['old_used_words'] = (int) $total_words_used;
                $result['current_used_words'] = (int) $total_words_used + $tokens;
            } else {
                // error log default message
                if (!empty($response['error']['message']))
                    error_log('OpenAI: ' . $response['error']['message']);

                $result['success'] = false;
                $result['api_error'] = $response['error']['message'];
                $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                die(json_encode($result));
            }
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function text_to_speech()
{
    $result = array();
    global $config;

    // if disabled by admin
    if (!get_option('enable_text_to_speech', 0)) {
        $result['success'] = false;
        $result['error'] = __('This feature is disabled by the admin.');
        die(json_encode($result));
    }

    if (checkloggedin()) {

        if (!$config['non_active_allow']) {
            $user_data = get_user_data(null, $_SESSION['user']['id']);
            if ($user_data['status'] == 0) {
                $result['success'] = false;
                $result['error'] = __('Verify your email address to use the AI.');
                die(json_encode($result));
            }
        }

        set_time_limit(0);

        $_POST = validate_input($_POST);

        if (!empty($_POST['description'])) {

            // check bad words
            if ($word = check_bad_words($_POST['description'])) {
                $result['success'] = false;
                $result['error'] = __('Your request contains a banned word:') . ' ' . $word;
                die(json_encode($result));
            }

            $membership = get_user_membership_detail($_SESSION['user']['id']);
            $characters_limit = $membership['settings']['ai_text_to_speech_limit'];

            $voices = get_ai_voices();

            if (get_option('enable_tts_translation')) {
                if(isset($_POST['translate_tts_text'])){
                    $words_limit = $membership['settings']['ai_words_limit'];

                    $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

                    if ($words_limit != -1){

                        $total_words_available = ($words_limit + get_user_option($_SESSION['user']['id'], 'total_words_available', 0)) - $total_words_used;

                        // check user's word limit
                        if ($total_words_available < str_word_count($_POST['description'])) {
                            $result['success'] = false;
                            $result['error'] = __('Words limit exceeded, Upgrade your membership plan.');
                            die(json_encode($result));
                        }
                    }

                    if (get_option('single_model_for_plans'))
                        $model = get_option('open_ai_model', get_default_openai_model());
                    else
                        $model = $membership['settings']['ai_model'];
                    $model = normalize_openai_model($model);

                    $language = $voices[$_POST['language']]['language'];
                    $prompt = "Translate this into $language language without any explanation:\n\n" . $_POST['description'] . "\n\n";

                    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
                    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

                    $open_ai = new Orhanerday\OpenAi\OpenAi(get_api_key());

                    if (array_key_exists($model, get_opeai_chat_models())) {
                        $complete = $open_ai->chat([
                            'model' => $model,
                            'messages' => [
                                [
                                    'role' => 'system',
                                    'content' => "You are a professional translator that can translate strings to $language language."
                                ],
                                [
                                    "role" => "user",
                                    "content" => "Translate, but avoid returning ending dots:\n\n" . $_POST['description'] . "\n\n"
                                ],
                            ],
                            'frequency_penalty' => 0,
                            'presence_penalty' => 0,
                            'temperature' => 0,
                            'user' => $_SESSION['user']['id']
                        ]);
                    } else {
                        $complete = $open_ai->completion([
                            'model' => $model,
                            'prompt' => "You are a professional translator that can translate strings to $language language. Translate, but avoid returning ending dots:\n\n" . $_POST['description'] . "\n\n",
                            'frequency_penalty' => 0,
                            'presence_penalty' => 0,
                            'temperature' => 0,
                            'user' => $_SESSION['user']['id']
                        ]);
                    }

                    $response = json_decode($complete, true);

                    if (isset($response['choices'])) {
                        if (array_key_exists($model, get_opeai_chat_models())) {
                            $_POST['description'] = $response['choices'][0]['message']['content'];
                        } else {
                            $_POST['description'] = $response['choices'][0]['text'];
                        }

                        $tokens = $response['usage']['completion_tokens'];

                        $word_used = ORM::for_table($config['db']['pre'] . 'word_used')->create();
                        $word_used->user_id = $_SESSION['user']['id'];
                        $word_used->words = $tokens;
                        $word_used->date = date('Y-m-d H:i:s');
                        $word_used->save();

                        update_user_option($_SESSION['user']['id'], 'total_words_used', $total_words_used + $tokens);
                    } else {
                        // error log default message
                        if (!empty($response['error']['message']))
                            error_log('OpenAI: ' . $response['error']['message']);

                        $result['success'] = false;
                        $result['api_error'] = $response['error']['message'];
                        $result['error'] = get_api_error_message($open_ai->getCURLInfo()['http_code']);
                        die(json_encode($result));
                    }
                }
            }

            $no_ssml_tags = preg_replace('/<[\s\S]+?>/', '', $_POST['description']);
            $text_characters = mb_strlen($no_ssml_tags, 'UTF-8');

            $start = date('Y-m-01');
            $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

            $total_character_used = get_user_option($_SESSION['user']['id'], 'total_text_to_speech_used', 0);

            // check user's character limit
            if ($characters_limit != -1 && ((($characters_limit + get_user_option($_SESSION['user']['id'], 'total_text_to_speech_available', 0)) - $total_character_used) <= $text_characters)) {
                $result['success'] = false;
                $result['error'] = __('Character limit exceeded, Upgrade your membership plan.');
                die(json_encode($result));
            }

            // check voice is available
            if (isset($voices[$_POST['language']]['voices'][$_POST['voice_id']])) {
                $voice = $voices[$_POST['language']]['voices'][$_POST['voice_id']];

                $text = preg_replace("/\&/", "&amp;", $_POST['description']);
                $text = preg_replace("/(^|(?<=\s))<((?=\s)|$)/i", "&lt;", $text);
                $text = preg_replace("/(^|(?<=\s))>((?=\s)|$)/i", "&gt;", $text);

                $ssml_text = "<speak>" . $text . "</speak>";

                require_once ROOTPATH . '/includes/lib/vendor/autoload.php';

                if($voice['vendor'] == 'aws') {
                    /* AWS TTS */
                    if(!get_option('enable_aws_tts')){
                        $result['success'] = false;
                        $result['error'] = __('AWS TTS is not enabled from the admin.');
                        die(json_encode($result));
                    }

                    if(empty(get_option('ai_tts_aws_access_key')) || empty(get_option('ai_tts_aws_secret_key'))){
                        $result['success'] = false;
                        $result['error'] = __('Please setup the AWS credentials in the admin.');
                        die(json_encode($result));
                    }

                    try {
                        $credentials = new \Aws\Credentials\Credentials(get_option('ai_tts_aws_access_key', ''), get_option('ai_tts_aws_secret_key', ''));
                        $client = new \Aws\Polly\PollyClient([
                            'region' => get_option('ai_tts_aws_region'),
                            'version' => 'latest',
                            'credentials' => $credentials
                        ]);
                    } catch (Exception $e) {
                        $result['success'] = false;
                        $result['error'] = __('Incorrect AWS credentials.');
                        $result['api_error'] = $e->getMessage();
                        die(json_encode($result));
                    }

                    $language = ($_POST['voice_id'] == 'ar-aws-std-zeina') ? 'arb' : $_POST['language'];

                    try {
                        // Create synthesize speech
                        $polly_result = $client->synthesizeSpeech([
                            'Engine' => $voice['voice_type'],
                            'LanguageCode' => $language,
                            'Text' => $ssml_text,
                            'TextType' => 'ssml',
                            'OutputFormat' => 'mp3',
                            'VoiceId' => $voice['voice'],
                        ]);

                        $audio_stream = $polly_result->get('AudioStream')->getContents();

                    } catch (Exception $e) {
                        $result['success'] = false;
                        $result['error'] = __('AWS Synthesize Speech is not working, please try again.');
                        $result['api_error'] = $e->getMessage();
                        die(json_encode($result));
                    }
                } else {
                    /* Google TTS */
                    if(!get_option('enable_google_tts')){
                        $result['success'] = false;
                        $result['error'] = __('Google TTS is not enabled from the admin.');
                        die(json_encode($result));
                    }

                    if(empty(get_option('ai_tts_google_json_path'))){
                        $result['success'] = false;
                        $result['error'] = __('Please setup the Google TTS credentials in the admin.');
                        die(json_encode($result));
                    }

                    $credentials = ROOTPATH .'/storage/' . get_option('ai_tts_google_json_path');
                    if(!file_exists($credentials)){
                        $result['success'] = false;
                        $result['error'] = __('GCS File (JSON) is not available in the "Storage" directory.');
                        die(json_encode($result));
                    }

                    try {
                        $gcp_client = new \Google\Cloud\TextToSpeech\V1\TextToSpeechClient([
                            'credentials' => json_decode(file_get_contents($credentials), true),
                        ]);
                    } catch (Exception $e) {
                        $result['success'] = false;
                        $result['error'] = __('Incorrect Google TTS credentials.');
                        $result['api_error'] = $e->getMessage();
                        die(json_encode($result));
                    }

                    try {
                        $input_text = (new \Google\Cloud\TextToSpeech\V1\SynthesisInput())
                            ->setSsml($ssml_text);

                        $input_voice = (new \Google\Cloud\TextToSpeech\V1\VoiceSelectionParams())
                            ->setLanguageCode($_POST['language'])
                            ->setName($voice['voice_id']);

                        $audio_config = (new \Google\Cloud\TextToSpeech\V1\AudioConfig())
                            ->setAudioEncoding(\Google\Cloud\TextToSpeech\V1\AudioEncoding::MP3);

                        $response = $gcp_client->synthesizeSpeech($input_text, $input_voice, $audio_config);
                        $audio_stream = $response->getAudioContent();
                    } catch (Exception $e) {
                        $result['success'] = false;
                        $result['error'] = __('Google TTS is not working, please try again.');
                        $result['api_error'] = $e->getMessage();
                        die(json_encode($result));
                    }
                }

                $name = uniqid() . '.mp3';

                $target_dir = ROOTPATH . '/storage/ai_audios/';
                file_put_contents($target_dir . $name, $audio_stream);

                $content = ORM::for_table($config['db']['pre'] . 'ai_speeches')->create();
                $content->user_id = $_SESSION['user']['id'];
                $content->title = $_POST['title'];
                $content->voice_id = $_POST['voice_id'];
                $content->language = $_POST['language'];
                $content->characters = $text_characters;
                $content->text = $_POST['description'];
                $content->file_name = $name;
                $content->vendor_id = $voice['vendor_id'];
                $content->created_at = date('Y-m-d H:i:s');
                $content->save();

                $speech_used = ORM::for_table($config['db']['pre'] . 'text_to_speech_used')->create();
                $speech_used->user_id = $_SESSION['user']['id'];
                $speech_used->characters = $text_characters;
                $speech_used->date = date('Y-m-d H:i:s');
                $speech_used->save();

                update_user_option($_SESSION['user']['id'], 'total_text_to_speech_used', $total_character_used + $text_characters);

                $result['success'] = true;
                $result['url'] = url('ALL_SPEECHES', false);
                die(json_encode($result));
            }
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function delete_speech()
{
    $result = array();
    if (checkloggedin()) {
        global $config;

        $speech = ORM::for_table($config['db']['pre'] . 'ai_speeches')
            ->select('file_name')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ));

        foreach ($speech->find_array() as $row) {
            $dir = "../storage/ai_audios/";
            $main_file = $row['file_name'];

            if (trim($main_file) != "") {
                $file = $dir . $main_file;
                if (file_exists($file))
                    unlink($file);
            }
        }

        if ($speech->delete_many()) {
            $result['success'] = true;
            $result['message'] = __('Deleted Successfully');
            die(json_encode($result));
        }
    }
    $result['success'] = false;
    $result['error'] = __('Unexpected error, please try again.');
    die(json_encode($result));
}

function add_email_subscriber() {
    global $config;

    $_POST = validate_input($_POST);
    if(!empty($_POST['email']) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){

        $already_exist = ORM::for_table($config['db']['pre'] . 'subscriber')
            ->where('email', $_POST["email"])
            ->find_one();

        if(empty($already_exist['email'])) {
            $subscriber = ORM::for_table($config['db']['pre'] . 'subscriber')->create();
            $subscriber->email = $_POST["email"];
            $subscriber->joined = date('Y-m-d');
            $subscriber->save();

            $result['success'] = true;
            $result['message'] = __("Subscribed Successfully.");
            die(json_encode($result));
        }
        $result['success'] = false;
        $result['message'] = __("This email is already exist.");
        die(json_encode($result));

    } else {
        $result['success'] = false;
        $result['message'] = __("This is not a valid email address");
        die(json_encode($result));
    }
}
