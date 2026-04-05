<?php
if (isset($current_user['id'])) {
    $social_profile = social_media_get_profile($_SESSION['user']['id']);
    $company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);
    $social_error = '';

    if (isset($_POST['company-intelligence-submit'])) {
        if (!check_allow()) {
            $social_error = "<span class='status-not-available'> Disabled on demo</span>";
        } else {
            $profileData = [
                'founder_name' => validate_input($_POST['founder_name']),
                'founder_title' => validate_input($_POST['founder_title']),
                'company_name' => validate_input($_POST['company_name']),
                'company_website' => validate_input($_POST['company_website']),
                'company_industry' => validate_input($_POST['company_industry']),
                'company_description' => validate_input($_POST['company_description']),
                'target_audience' => validate_input($_POST['target_audience']),
                'brand_voice' => validate_input($_POST['brand_voice']),
                'content_goals' => validate_input($_POST['content_goals']),
                'key_products' => validate_input($_POST['key_products']),
                'differentiators' => validate_input($_POST['differentiators']),
                'ideal_customer_profile' => validate_input($_POST['ideal_customer_profile']),
                'top_problems_solved' => social_media_normalize_list(isset($_POST['top_problems_solved']) ? $_POST['top_problems_solved'] : []),
                'unique_selling_points' => social_media_normalize_list(isset($_POST['unique_selling_points']) ? $_POST['unique_selling_points'] : []),
                'instagram_handle' => validate_input($_POST['instagram_handle']),
                'brand_colors' => social_media_normalize_color_list(isset($_POST['brand_colors']) ? $_POST['brand_colors'] : []),
                'visual_mood' => social_media_normalize_list(isset($_POST['visual_mood']) ? $_POST['visual_mood'] : []),
                'tone_attributes' => social_media_normalize_list(isset($_POST['tone_attributes']) ? $_POST['tone_attributes'] : []),
                'reference_brands' => social_media_normalize_list(isset($_POST['reference_brands']) ? $_POST['reference_brands'] : []),
                'competitors' => social_media_normalize_list(isset($_POST['competitors']) ? $_POST['competitors'] : []),
                'competitor_notes' => validate_input($_POST['competitor_notes']),
                'website_snapshot' => $social_profile['website_snapshot'],
                'website_extracted_at' => $social_profile['website_extracted_at'],
                'moodboard_images' => $social_profile['moodboard_images'],
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
            if (!empty($_FILES['company_logo']['name'])) {
                $profileData['company_logo'] = social_media_upload_company_logo($_SESSION['user']['id'], $social_profile['company_logo']);
            } else {
                $profileData['company_logo'] = $social_profile['company_logo'];
            }
            $profileData['moodboard_images'] = social_media_upload_moodboard_images($_SESSION['user']['id'], $profileData['moodboard_images']);

            $social_profile = social_media_save_profile($_SESSION['user']['id'], $profileData);
            transfer($link['COMPANY_INTELLIGENCE'], __("Company intelligence saved successfully"), __("Settings Saved Successfully"));
            exit;
        }
    }

    $reference_brand_snapshots = social_media_get_reference_brand_snapshots($social_profile);

    try {
        HtmlTemplate::display('global/company-intelligence', [
            'social_profile' => $social_profile,
            'company_intelligence' => $company_intelligence,
            'reference_brand_snapshots' => $reference_brand_snapshots,
            'social_error' => $social_error,
            'current_avatar' => !empty($current_user['image']) ? $current_user['image'] : 'default_user.png',
        ]);
        exit;
    } catch (Throwable $e) {
        echo '<div style="max-width:900px;margin:40px auto;padding:24px;font-family:Arial,sans-serif;background:#fff;border:1px solid #eee;border-radius:16px">';
        echo '<h1 style="margin:0 0 10px;font-size:32px;">Company Intelligence Debug</h1>';
        echo '<p style="margin:0 0 16px;color:#666;">The page crashed while rendering. This debug view is temporary so we can identify the exact issue on the live server.</p>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . (int) $e->getLine() . '</p>';
        echo '</div>';
        exit;
    }
}

error(__("Page Not Found"), __LINE__, __FILE__, 1);
exit();
