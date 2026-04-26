<?php
overall_header(__("Register"));
$logo_dark = !empty($config['site_logo']) ? $config['site_url'] . 'storage/logo/' . $config['site_logo'] : '';
?>
<?php print_adsense_code('header_bottom'); ?>
<div class="container atlas-login-shell">
    <div class="row">
        <div class="col-xl-10 col-lg-11 col-md-12 mx-auto">
            <div class="atlas-auth-stage atlas-auth-stage-wide">
                <div class="atlas-auth-story">
                    <div class="atlas-auth-story-inner">
                        <span class="atlas-auth-eyebrow"><?php _e("Atlas Studio Access") ?></span>
                        <h1><?php _e("Launch your brand workspace in one step") ?></h1>
                        <p><?php _e("Create your account and we will connect your brand studio, website studio, and campaign systems into one guided workspace.") ?></p>

                        <div class="atlas-auth-promise">
                            <div class="atlas-auth-promise-card">
                                <strong><?php _e("Brand setup") ?></strong>
                                <span><?php _e("Turn your business idea into a usable identity system.") ?></span>
                            </div>
                            <div class="atlas-auth-promise-card">
                                <strong><?php _e("Website draft") ?></strong>
                                <span><?php _e("Move directly toward a ready-made public presence instead of starting from scratch.") ?></span>
                            </div>
                            <div class="atlas-auth-promise-card">
                                <strong><?php _e("Campaign engine") ?></strong>
                                <span><?php _e("Prepare creative directions, offers, and outreach assets from the same workspace.") ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login-register-page atlas-login-page atlas-auth-card">
                    <div class="welcome-text atlas-login-brand atlas-login-brand-left">
                        <?php if (!empty($logo_dark)) { ?>
                            <img src="<?php _esc($logo_dark) ?>" alt="<?php _esc($config['site_title']) ?>" class="atlas-login-logo">
                        <?php } else { ?>
                            <h3><?php _esc($config['site_title']) ?></h3>
                        <?php } ?>
                        <span><?php _e("Create your Hatchers AI workspace") ?></span>
                    </div>

                    <div class="atlas-auth-heading">
                        <h2><?php _e("Open your account") ?></h2>
                        <p><?php _e("We will use these details to set up your studio, protected workspace, and first launch flow.") ?></p>
                    </div>

                    <?php if($config['facebook_app_id'] != "" || $config['google_app_id'] != ""){ ?>
                        <div class="social-login-buttons">
                            <?php if($config['facebook_app_id'] != ""){ ?>
                                <button class="facebook-login ripple-effect" onclick="fblogin()">
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53d3cudzMub3JnLzE5OTkveGxpbmsiPgo8cmVjdCB4PSIyIiB5PSIyIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAuMTI1OCIgZmlsbD0idXJsKCNwYXR0ZXJuMCkiLz4KPGRlZnM+CjxwYXR0ZXJuIGlkPSJwYXR0ZXJuMCIgcGF0dGVybkNvbnRlbnRVbml0cz0ib2JqZWN0Qm91bmRpbmdCb3giIHdpZHRoPSIxIiBoZWlnaHQ9IjEiPgo8dXNlIHhsaW5rOmhyZWY9IiNpbWFnZTBfMTAwMTRfMzYyMDIiIHRyYW5zZm9ybT0ic2NhbGUoMC4wMDYyODkzMSAwLjAwNjI1KSIvPgo8L3BhdHRlcm4+CjxpbWFnZSBpZD0iaW1hZ2UwXzEwMDE0XzM2MjAyIiB3aWR0aD0iMTU5IiBoZWlnaHQ9IjE2MCIgeGxpbms6aHJlZj0iZGF0YTppbWFnZS9wbmc7YmFzZTY0LGlWQk9SdzBLR2dvQUFBQU5TVWhFVWdBQUFKOEFBQUNnQ0FZQUFBQVNONzZZQUFBQUNYQklXWE1BQUJjUkFBQVhFUUhLSnZNL0FBQUs3VWxFUVZSNG5PMmRYNGhjVngzSHowNkpyaWJNYnRJRzYxaHFwd1ZCTVppVjBwZStaUE1tRk1tRzRrT2xrQTJJYitJS0xRdytiUkJ4WHNRcFBnaFM2Qzcwb3cvazVFRkJueWFnQlZGeEkyb2YxR2JYdEwwMmJwck1Uc3dtN2U2TUhQME5lNWx6N3AzNzUveTk1L3VCWVpKNzcremVtZk0zNy96N25YT21oc01oQThBRzAvalVnUzBnSDdBRzVBUFdnSHpBR3JQNDZJK29OL3RMalRHVGpsZWw2aWt3M0cySlBDQzlLNVJtZTM0NCtvVmV1bXZpb2dnbTN0a21qTEpCaC9uQlV1MHNjT1kyeUxIdDFRaFJ4R3ZucXp6NlBYQ2duSEg0dkNSWGJoa1pKTDJJbGF0UzNIN2swTGxaYVBvdHNxeVdZeXNwV2x4eVVrRVR0ZTNYY3VLaWRmTE1LdEZhaW51Y2hJeEhiVklsSmw1S3MzK3lzVTVTNElKNnNEcnl1MkdXTWJVYXQyMS9kMzViVjg5V2IvSkVXNTlZcEV1YXlNb3VGNjFLcHQrM0hMSWw3S1I5S3QwY08xaG9OcE5uMlYwRHY1NnMzK09xU1Q0cDJFM3NoWGIvWlhBeXhlaTNDRkdpZk8xd21kbDQrNlMzZ2wrNXh3RWlUQjY0UnJVYXUya1hEZUNaeWhoaXpwMVBOSjlYVGdKc3NJN3JsZGRMWXFkVEN5b04vdkxOUFFFOGNyQlM0c2JWRTkyRHVjaVg3M1piME02TFRnWEJaMlJqK3AyRzU0TmcvbUdVM1ZCSjRwZGFzbDJJWjUyZVBmVTYvVm0zd241ckVjK0ZMUFd1TTRUTG14MnlWaVRqMXF6R3hVZmkzV2RIZ2xvSldIQlNyRkw0blVobm5WNE1keWxwQXpqR0plUEdoYmJxTjg1QXhmd1oxVHZOb3BSK1VpOExzWmxuZVIxMHdJYWt3L2llWUZSQVkzSUIvRzh3cGlBMnVXRGVGNWlSRUN0WFMwMG4ySUw0bm5MWloyaklkcmtpM1dub0ZYck4rZDF6U3ZXV2V4Q3ZHclFvYXFUY3JUSVIyT0hFSzhhTEpLQUoxVy9HK1h5VVVYMWtuQUMrTXlUTkZ0T0tVcnJmQlNlL3lpY0NKalBmbUthTFQwK3MvK1ordlN0YzUrYW5aMlpaZ2VQbnBoYVdKaWJPalgrcVh4NHlQYmZ2anU0TmZyLzczY09aL2NlREEvZTJ4c3UvT0dmaDZkNkQ0YnNMKzhPYkg2WVY2SldUVmxpcWpMNUtDeHZoVDdCWjJGK2luM3h6T3oraTg4ZTIvM2M0ek9uajgyd2VlR2lrdHg3T0x6OTczdkRlMXpPdjkwYUhQOSs5d05CWkkwb2E0Q29sSzhUY3FMQWMwL1BzRzkrNGFNM24vbmt6QlBDU2MzVW0zMlR2NDVud2pSVXBHSXBXWitQc2lLQ0ZJOUw5KzBMYys5OStySHB4eGhqeHNXendDS2x3cFhPaENrZCthaTQzUTZ0SS9tSlU5T3MvYVc1M2VlZW5qa3RuRFNNNGNnMzRtTFpGYlJVdEhZM1FoUHYrVE96OTMvejhvbDlGOFN6eUViWjdwZFM4b1ZZM0g3M2hibmQxMTZhUDY2akllRVppelNadnpDRjVTUHJTLzF5MzNqdHBmbjN2L3pzc1pDajNUaVhhSTUxSWNwRXZxb3N2cGdKTHQ3eloyWWY4ZUJXVFZNNEFCV1NqN0pWMW9RVEZZVVh0UkF2a2JORjA2K0tScjcxVUJvWnZIR0JvbllpN1NLTmo5enlVZFFMWXV5V2Q2Zjg0TVg1S2VFRUdHZXhTRWxZSlBJNXVlaU1EbmcvSGxxMW1WbkxHLzF5eVJkUzFPTWpGNEgzNCtVbGQvVExHL21DaVhwOHlFdzRDQ2FoUno0S3FjRkVQUnFyQmZsWXpOUHl6UlA1Z3VsYTRka3B3a0dRbGN5ZTVKSFArSElLTnVENWVEYlNvaXJFMmF5akhwbmtvekhjSUVZemVDS29jQkRrSlZPZ3locjVnb2g2SEo2QkxCd0VlYm1VcGR0bG9uejBRNExKWE9HcDc4SkJVSVNKeWFaWk1wbXRyTjFtQXo3WngxYW44dDZENFoxZi92WGcvbTl2SEQ1eTQvYmdmL2Znd0lTaE1xeFFybWNpa0M4R24yWEdtRm41M3UwTmQ3LzJrLzNUYjd4MXlDY0JtWndJcEpzTHZOUk1tK3VSV3V5R1Z1VHk2WTNDUVkzOC9NOEg3ei96blh0Y1BOTnYxUlNwZ1N0VnZwQ2lIb2ZQcXhVT2FvS0w5NVVmN1ZjOVRTdlZuMG55RmM1UzlSRStvZHZFYmZmMmg3MEF4R09UU2sxRXZoanp4NmJtaElNYStOWXZIbjdFempzMFQxcUhjNko4dFBSRlVMUFNQcjR3cFgwOGx5K0o4ZVBmZlJoU21sWisrZEplQklyenAzY09RK3ZFVHZRSThobG02KzFEYnp2dUNwSzRUM0thZkZvV0JBeWRONlBCeDBMN0NKTHFmVkw1cUg4UDI4bHJZRFI2RVJqU1FDYVZMK2xpQUFyU2tMMHNTVDVwbUFTZ0lOSmdsaVNmOHZWM1FkQklHeDFKOGtsTkJhQW9zdnkrSlBta1pUUUFKUkFDV3BKOGFPa0M3UWp5eWNJakFBb1FHckdDZkxMd0NJQU9aUElCb0FPaFJJVjh3QlJDaVNxVFQ3Z0lBQjNJNUJQQ0l3Q201QVBBQ0pBUFdNUFliQzFkdlBIS2laMm5IcDMycGxQOHAxODlMaHdyaTZVZGlFcUR5T2M1ZkU2SXIrOEE4bmxPZkg5ZTM1REpsN2k4QVhBUHZ1ZHVsZVRiRW80QVorR2JQZnY2MTVISkJ6eUNiMy92eWQwS1FRM3llYzdOTzJwMmlqZUFVSjJUeVNjWUN0emw1aDEvcHdFTDhxV3Rwd2JjNHNidHdZNUhmNUx1K0FGQlBzS25OeFVzYiswT2t2NStYcEIwODl2Q0VlQWNmNzgxOEdtMUs2RTZseVNmY0NGd2oxKzllZUROTGtteTZseVNmTUtGd0QzNGd1R2VjRTEybTBueUNaVkQ0QjRlclZRdkxVbVQ1Sk5lRE56aFgzdERuM2JGbExZaHBQSlIrWXdXcjhPOGMzZndnVWUzS3cxbVV2blNYZ0Rjd0tkRkpxTldUVnFOUzVOUCtnTGdCci8reDZFdkNiVFN4Z2FEZlA2eXQrOU5TemZSbzBUNW9sYU5GN3M5NFFSd0FvOTJMY292SDlFUmpqakd3d05tWk84TWw3aHpmK2pOcEkyaytoN0xNSUdJdi9DU2NOUWh6bi92UDhwNithTldUVGltbWhkK2VGOUYxTkovbzJxNG12WlR2STk4d0dsUy9VbVZqL3I3VXUwRklJWGk4aEdwUHdDQUJLN0trZ2tnSHpEQlJHOG15b2VpRnhTa3ZIeEU2bDc1QUl5eG1XVTZSaWI1b2xhdGcwUURrSU5Nd1NyUEhBQkVQNUNGNjJrZHkzSHl5TmNXamdBZ2t0bVR6UEpSR2I0cG5BRGdpSjJvVmN0Y1F1YWRlcmN1SEFIZ2lGeFZzMXp5UmEzYU5xSWZTS0NYdDJwV1pOSXhvaCtRMGM2NzJrVnUrUkQ5Z0lTZElnM1Nvc3N0ckNQUkZNUllMN0xHVHlINUtQcWg2d1V3NnRjcjFBZGNacUdaTmtZOUFHTnNyZWlIVUZnK0NyT3J3Z2tRRXB0WlJ6TmtsRnBpaTM0eE1sN0NwRmNtNmpGRnkrS3VvdkVSSkt0bEZ4SXRMUitLM3lDNVNwbE9wVkN5c2lYZENQcit3cUNuS3Rpb1hGWjFEYTNmSUZoUnRXNjNNdm5vaGxhRUU2QktYQ25UdWgxSDZZTFN0TVRHWmVFRXFBTFhvbFpONmJpKzh0WE1xYmNiOWI5cWNWMUhxYVpsS2Yyb1ZWdE5XeG9MZUVWUFJiZUtESjM3T0t6UU53YjR6UXBWcDVTalRUNzZwaXlqQTlwckxxdHNZSXlqZFFjYkNPZzFsNHRtcTJSRisvWkpGTElob0Y5b0Y0K1oydklVQW5xRkVmR1l5ZjEySWFBWEdCT1BtZDdzT1NZZ2h1SGN3Nmg0ek1aTzR5VGdFcnBobklHWFJCZE5pOGRzYlhNZmF3VWpFZFV1WEx4bEZlbFJSYkMyV1RBWE1HclZlRWYwcThKSllBSmU4alIwZFNCbndmcE8xVkdyeGxPeExxSWhZcFJYbzFadFNjZVFXUjZjMkNhZHdqN3FnZm9aMWU5S3piMVFoVE43OVBPNXdQemJ5SFBHaEpOQUJUelJZOGxXL1U2R00vS05vSnl4OCtpT1VRYVBkdCtJV3JWbG11enZETTdKeDQ2bVpDSUtsbWNVN1p4Y1hjSkorZGhSYTVoSHdjOGpOekEzUGVvMGRpN2F4WEZXdmhHOEs0Qi9pTlFpUmxHY1RvOUtpNGFOVHVPOFROcjR6eG1vb3R5cE4vdHJ0RXJXb2kvM2JvaE5XaTNLMlVnM2p2T1JieHlxdnpUb0c0Nit3ZjlMOXhTZnV1Q1RlTXlueUJlSE9rZDU5RnV2Ti91cjlHOWZ0bjFYUVkvV1AyNzdKbHdjTCtXTFEzV2JqWHF6djB3VDF5OElGMVdINjdRMFhjZjI2SVFLdkpkdkJIWFBkT3ZOZm9NbUwvR0llRmE0MEQ5NnRJOVoyK1k0ckE0cUk5K0kyS3FwN1hxenYwUWlybmdtNGtpNGprc2pFcXFwbkh4eEtGSnNVZDJ3UVdsY0svVHNXbXVaOTJWMlNiaEtSYmdrS2kxZkhJcUlHNk9OU2lncXhoL25oQmZwZy9kWGpyNFlYWjNURTExbWFqZ2NodmkrazJqUVk1bk9qNTRiT1Z2VFBSS0wwZlBkMkhPUW9zbUFmTUFhM25VeWcrb0ErWUExSUIrd0J1UURkbUNNL1JjenBaTDVrNUxrcFFBQUFBQkpSVTVFcmtKZ2dnPT0iLz4KPC9kZWZzPgo8L3N2Zz4K"> <?php _e("Log In via Facebook") ?>
                                </button>
                            <?php } ?>
                            <?php if($config['google_app_id'] != ""){ ?>
                                <button class="google-login ripple-effect" onclick="gmlogin()">
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMC42NCAxMi4yMDQ3QzIwLjY0IDExLjU2NjUgMjAuNTgyNyAxMC45NTI5IDIwLjQ3NjQgMTAuMzYzOEgxMlYxMy44NDUxSDE2Ljg0MzZDMTYuNjM1IDE0Ljk3MDEgMTYuMDAwOSAxNS45MjMzIDE1LjA0NzcgMTYuNTYxNVYxOC44MTk3SDE3Ljk1NjRDMTkuNjU4MiAxNy4yNTI5IDIwLjY0IDE0Ljk0NTYgMjAuNjQgMTIuMjA0N1oiIGZpbGw9IiM0Mjg1RjQiLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMS45OTk4IDIxLjAwMDFDMTQuNDI5OCAyMS4wMDAxIDE2LjQ2NyAyMC4xOTQyIDE3Ljk1NjEgMTguODE5NkwxNS4wNDc1IDE2LjU2MTRDMTQuMjQxNiAxNy4xMDE0IDEzLjIxMDcgMTcuNDIwNSAxMS45OTk4IDE3LjQyMDVDOS42NTU2NyAxNy40MjA1IDcuNjcxNTggMTUuODM3NCA2Ljk2Mzg1IDEzLjcxMDFIMy45NTcwM1YxNi4wNDE5QzUuNDM3OTQgMTguOTgzMyA4LjQ4MTU4IDIxLjAwMDEgMTEuOTk5OCAyMS4wMDAxWiIgZmlsbD0iIzM0QTg1MyIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTYuOTY0MDkgMTMuNzA5OEM2Ljc4NDA5IDEzLjE2OTggNi42ODE4MiAxMi41OTMgNi42ODE4MiAxMS45OTk4QzYuNjgxODIgMTEuNDA2NiA2Ljc4NDA5IDEwLjgyOTggNi45NjQwOSAxMC4yODk4VjcuOTU4MDFIMy45NTcyN0MzLjM0NzczIDkuMTczMDEgMyAxMC41NDc2IDMgMTEuOTk5OEMzIDEzLjQ1MjEgMy4zNDc3MyAxNC44MjY2IDMuOTU3MjcgMTYuMDQxNkw2Ljk2NDA5IDEzLjcwOThaIiBmaWxsPSIjRkJCQzA1Ii8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTEuOTk5OCA2LjU3OTU1QzEzLjMyMTEgNi41Nzk1NSAxNC41MDc1IDcuMDMzNjQgMTUuNDQwMiA3LjkyNTQ1TDE4LjAyMTYgNS4zNDQwOUMxNi40NjI5IDMuODkxODIgMTQuNDI1NyAzIDExLjk5OTggM0M4LjQ4MTU4IDMgNS40Mzc5NCA1LjAxNjgyIDMuOTU3MDMgNy45NTgxOEw2Ljk2Mzg1IDEwLjI5QzcuNjcxNTggOC4xNjI3MyA5LjY1NTY3IDYuNTc5NTUgMTEuOTk5OCA2LjU3OTU1WiIgZmlsbD0iI0VBNDMzNSIvPgo8L3N2Zz4K"> <?php _e("Log In via Google") ?>
                                </button>
                            <?php } ?>
                        </div>
                        <div class="social-login-separator"><span><?php _e("or") ?></span></div>
                    <?php } ?>

                    <form method="post" id="register-account-form" action="#" accept-charset="UTF-8" onsubmit="document.getElementById('submit-btn').disabled = true;">
                        <div class="form-group">
                            <div class="input-with-icon-left">
                                <i class="la la-user"></i>
                                <input type="text" class="input-text with-border" placeholder="<?php _e("Full Name") ?>" value="<?php _esc($name_field)?>" id="name" name="name" onBlur="checkAvailabilityName()" required/>
                            </div>
                            <span id="name-availability-status"><?php if($name_error != ""){ _esc($name_error) ; }?></span>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon-left">
                                <i class="la la-user"></i>
                                <input type="text" class="input-text with-border" placeholder="<?php _e("Username") ?>" value="<?php _esc($username_field)?>" id="Rusername" name="username" onBlur="checkAvailabilityUsername()" required/>
                            </div>
                            <span id="user-availability-status"><?php if($username_error != ""){ _esc($username_error) ; }?></span>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon-left">
                                <i class="la la-envelope"></i>
                                <input type="text" class="input-text with-border" placeholder="<?php _e("Email Address") ?>" value="<?php _esc($email_field)?>" name="email" id="email" onBlur="checkAvailabilityEmail()" required/>
                            </div>
                            <span id="email-availability-status"><?php if($email_error != ""){ _esc($email_error) ; }?></span>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon-left">
                                <i class="la la-unlock"></i>
                                <input type="password" class="input-text with-border" placeholder="<?php _e("Password") ?>" id="Rpassword" name="password" onBlur="checkAvailabilityPassword()" required/>
                            </div>
                            <span id="password-availability-status"><?php if($password_error != ""){ _esc($password_error) ; }?></span>
                        </div>
                        <div class="form-group margin-bottom-15">
                            <div class="atlas-auth-note">
                                <i class="fa fa-shield"></i>
                                <span><?php _e("Your workspace is protected. Use a strong password so your brand and campaign data stay secure.") ?></span>
                            </div>
                            <div class="text-center">
                                <?php
                                if($config['recaptcha_mode'] == '1'){
                                    echo '<div class="g-recaptcha" data-sitekey="'._esc($config['recaptcha_public_key'],false).'"></div>';
                                }
                                ?>
                            </div>
                            <span><?php if($recaptcha_error != ""){ _esc($recaptcha_error) ; }?></span>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="agree_for_term" name="agree_for_term" value="1" required>
                            <label for="agree_for_term"><span class="checkbox-icon"></span> <?php _e("By creating your account you agree to our") ?> <?php _e("Terms & Condition") ?></label>
                        </div>
                        <input type="hidden" name="submit" value="submit">
                        <button id="submit-btn" class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit"><?php _e("Create Workspace") ?> <i class="icon-feather-arrow-right"></i></button>
                    </form>

                    <div class="atlas-login-footnote">
                        <span><?php _e("Already have access?") ?> <a href="<?php url("LOGIN") ?>"><?php _e("Log in to Atlas Studio") ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="atlas-login-footer">
    <div class="atlas-login-footer-copy">2026 Hatchers.ai, All right reserved</div>
    <div class="atlas-login-footer-social">
        <?php if ($config['facebook_link'] != "") { ?>
            <a href="<?php _esc($config['facebook_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
        <?php } ?>
        <?php if ($config['instagram_link'] != "") { ?>
            <a href="<?php _esc($config['instagram_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
        <?php } ?>
        <?php if ($config['twitter_link'] != "") { ?>
            <a href="<?php _esc($config['twitter_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="X"><i class="fa fa-twitter"></i></a>
        <?php } ?>
        <?php if ($config['youtube_link'] != "") { ?>
            <a href="<?php _esc($config['youtube_link'], false) ?>" target="_blank" rel="nofollow noopener" aria-label="YouTube"><i class="fa fa-youtube"></i></a>
        <?php } ?>
    </div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
    .atlas-login-shell {
        min-height: calc(100vh - 170px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .atlas-login-shell > .row {
        width: 100%;
        justify-content: center;
    }

    .atlas-auth-stage {
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        gap: 28px;
        align-items: stretch;
    }

    .atlas-auth-stage-wide {
        max-width: 1160px;
        margin: 0 auto;
    }

    .atlas-auth-story,
    .atlas-auth-card {
        min-height: 72vh;
        border-radius: 28px;
    }

    .atlas-auth-story {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.7), transparent 45%),
            linear-gradient(145deg, #f5efe7 0%, #ece3d6 100%);
        box-shadow: 0 30px 80px rgba(67, 45, 19, 0.12);
    }

    .atlas-auth-story::after {
        content: "";
        position: absolute;
        inset: auto -12% -16% 35%;
        height: 290px;
        background: radial-gradient(circle, rgba(196, 156, 107, 0.28), transparent 68%);
        pointer-events: none;
    }

    .atlas-auth-story-inner {
        position: relative;
        z-index: 1;
        padding: 54px 48px;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: center;
    }

    .atlas-auth-eyebrow {
        display: inline-flex;
        width: fit-content;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.68);
        color: #6f655b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }

    .atlas-auth-story h1 {
        font-size: clamp(34px, 4vw, 52px);
        line-height: 1.04;
        letter-spacing: -0.04em;
        margin-bottom: 18px;
        color: #171717;
    }

    .atlas-auth-story p {
        font-size: 17px;
        line-height: 1.75;
        color: #5f564e;
        max-width: 560px;
        margin-bottom: 28px;
    }

    .atlas-auth-promise {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .atlas-auth-promise-card {
        padding: 18px 18px 16px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(133, 109, 80, 0.12);
    }

    .atlas-auth-promise-card strong {
        display: block;
        color: #171717;
        font-size: 15px;
        margin-bottom: 6px;
    }

    .atlas-auth-promise-card span {
        display: block;
        color: #6a6259;
        font-size: 14px;
        line-height: 1.65;
    }

    .atlas-login-page {
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
        margin: 0 auto;
    }

    .atlas-auth-card {
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 30px 80px rgba(59, 40, 18, 0.12);
        padding: 42px 38px;
    }

    .atlas-login-brand {
        margin-bottom: 20px;
        text-align: center;
    }

    .atlas-login-brand-left {
        text-align: left;
    }

    .atlas-login-logo {
        display: block;
        width: auto;
        max-width: 220px;
        max-height: 72px;
        margin: 0 0 14px;
    }

    .atlas-login-brand-left .atlas-login-logo {
        margin-left: 0;
    }

    .atlas-login-brand span {
        color: #8d877f;
        font-size: 15px;
        font-weight: 500;
    }

    .atlas-auth-heading {
        margin-bottom: 22px;
    }

    .atlas-auth-heading h2 {
        font-size: 30px;
        line-height: 1.08;
        letter-spacing: -0.03em;
        margin-bottom: 10px;
    }

    .atlas-auth-heading p {
        margin: 0;
        color: #756d64;
        line-height: 1.7;
        font-size: 15px;
    }

    .atlas-auth-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        padding: 14px 16px;
        border-radius: 16px;
        background: #f7f2ec;
        color: #6f655b;
        font-size: 14px;
        line-height: 1.65;
    }

    .atlas-auth-note i {
        margin-top: 3px;
        color: #b17d45;
    }

    .atlas-login-footnote {
        margin-top: 22px;
        text-align: center;
        color: #8d877f;
        font-size: 14px;
    }

    .atlas-login-footer {
        width: min(1320px, calc(100% - 48px));
        margin: 0 auto 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        color: #8d877f;
        font-size: 15px;
    }

    .atlas-login-footer-social {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .atlas-login-footer-social a {
        color: #4f4a43;
        font-size: 18px;
        line-height: 1;
    }

    .atlas-login-footer-social a:hover {
        color: #171717;
    }

    @media (max-width: 991px) {
        .atlas-auth-stage {
            grid-template-columns: 1fr;
        }

        .atlas-auth-story,
        .atlas-auth-card {
            min-height: auto;
        }

        .atlas-auth-story-inner,
        .atlas-auth-card {
            padding: 34px 26px;
        }
    }

    @media (max-width: 767px) {
        .atlas-login-footer {
            width: calc(100% - 32px);
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>
<script>
    var error = "";

    function checkAvailabilityName() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>global/check_availability.php",
            data: 'name=' + $("#name").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $("#name").removeClass('has-success');
                    $("#name-availability-status").html(data);
                    $("#name").addClass('has-error mar-zero');
                }
                else {
                    error = 0;
                    $("#name").removeClass('has-error mar-zero');
                    $("#name-availability-status").html("");
                    $("#name").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
    function checkAvailabilityUsername() {
        var $item = $("#Rusername").closest('.form-group');
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>global/check_availability.php",
            data: 'username=' + $("#Rusername").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $item.removeClass('has-success');
                    $("#user-availability-status").html(data);
                    $item.addClass('has-error');
                }
                else {
                    error = 0;
                    $item.removeClass('has-error');
                    $("#user-availability-status").html("");
                    $item.addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
    function checkAvailabilityEmail() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>global/check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $("#email").removeClass('has-success');
                    $("#email-availability-status").html(data);
                    $("#email").addClass('has-error mar-zero');
                }
                else {
                    error = 0;
                    $("#email").removeClass('has-error mar-zero');
                    $("#email-availability-status").html("");
                    $("#email").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
    function checkAvailabilityPassword() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>global/check_availability.php",
            data: 'password=' + $("#Rpassword").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $("#Rpassword").removeClass('has-success');
                    $("#password-availability-status").html(data);
                    $("#Rpassword").addClass('has-error mar-zero');
                }
                else {
                    error = 0;
                    $("#Rpassword").removeClass('has-error mar-zero');
                    $("#password-availability-status").html("");
                    $("#Rpassword").addClass('has-success');
                }
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
</script>
<?php
overall_footer();
?>
