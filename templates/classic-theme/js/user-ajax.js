jQuery(function ($) {
    "use strict";

    function escape_html(text) {
        return $('<div>').text(text || '').html();
    }

    function download_text_file(filename, text) {
        var blob = new Blob([text || ''], { type: 'text/plain;charset=utf-8' });
        var url = URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    function slugify_filename(text) {
        return String(text || 'caption')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '') || 'caption';
    }

    // email resend
    $('.resend').on('click', function (e) { 						// Button which will activate our modal
        var the_id = $(this).attr('id');						//get the id
        // show the spinner
        $(this).html("<i class='fa fa-spinner fa-pulse'></i>");
        $.ajax({											//the main ajax request
            type: "POST",
            data: "action=email_verify&id=" + $(this).attr("id"),
            url: ajaxurl,
            success: function (data) {
                $("span#resend_count" + the_id).html(data);
                //fadein the vote count
                $("span#resend_count" + the_id).fadeIn();
                //remove the spinner
                $("a.resend_buttons" + the_id).remove();

            }
        });
        return false;
    });

    // user login
    $("#login-form").on('submit', function (e) {
        e.preventDefault();
        $("#login-status").slideUp();
        $('#login-button').addClass('button-progress').prop('disabled', true);
        var form_data = {
            action: 'ajaxlogin',
            username: $("#username").val(),
            password: $("#password").val(),
            is_ajax: 1
        };
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function (response) {
                $('#login-button').removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $("#login-status").addClass('success').removeClass('error').html('<p>' + LANG_LOGGED_IN_SUCCESS + '</p>').slideDown();
                    window.location.href = response.message;
                } else {
                    $("#login-status").removeClass('success').addClass('error').html('<p>' + response.message + '</p>').slideDown();
                }
            }
        });
        return false;
    });

    $("#newsletter-form").on('submit', function (e) {
        e.preventDefault();
        $("#newsletter-form-status").slideUp();
        var $btn = $(this).find('.btn');
        $btn.addClass('button-progress').prop('disabled', true);
        var form_data = {
            action: 'add_email_subscriber',
            email: $(".newsletter-email").val(),
        };
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: form_data,
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $("#newsletter-form-status").addClass('success').removeClass('error').html(response.message ).slideDown();
                    $(".newsletter-email").val('');
                } else {
                    $("#newsletter-form-status").removeClass('success').addClass('error').html(response.message).slideDown();
                }
                setTimeout(function() {
                    $('#newsletter-form-status').slideUp();
                }, 5000);
            }
        });
        return false;
    });

    // blog comment with ajax
    $('.blog-comment-form').on('submit', function (e) {
        e.preventDefault();

        var action = 'submitBlogComment';
        var data = $(this).serialize();
        var $parent_cmnt = $(this).find('#comment_parent').val();
        var $cmnt_field = $(this).find('#comment-field');
        var $btn = $(this).find('.button');
        $btn.addClass('button-progress').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    if ($parent_cmnt == 0) {
                        $('.latest-comments > ul').prepend(response.html);
                    } else {
                        $('#li-comment-' + $parent_cmnt).after(response.html);
                    }
                    $('html, body').animate({
                        scrollTop: $("#li-comment-" + response.id).offset().top
                    }, 2000);
                    $cmnt_field.val('');
                } else {
                    $('#respond > .widget-content').prepend('<div class="notification error"><p>' + response.error + '</p></div>');
                }
            }
        });
    });

    /* generate content */
    $('#ai_form').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var action = 'generate_content';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();

        data = $form.serialize();

        let eventSource = new EventSource(`${ajaxurl}?action=${action}&${data}`);

        let msg = tinymce.activeEditor.getContent();
        if (msg) {
            msg += '\n\n'
        }

        let ENABLE_STREAMING = true;

        eventSource.onmessage = function (e) {
            if (e.data === "[DONE]") {
                $btn.removeClass('button-progress').prop('disabled', false);

                if(!ENABLE_STREAMING) {
                    let str = msg;

                    str = str.replace(/```(\w+)?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
                    str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');

                    tinymce.activeEditor.setContent(str);
                }

                eventSource.close();

            } else {
                let error = JSON.parse(e.data).error;
                if (error !== undefined) {
                    console.log(e.data);
                    eventSource.close();
                    $btn.removeClass('button-progress').prop('disabled', false);
                    $error.html(error).slideDown().focus();
                    return;
                }

                let data = JSON.parse(e.data);
                let txt = data.choices[0].delta.content;
                if (txt !== undefined) {
                    msg = msg + txt;

                    if(!ENABLE_STREAMING) {
                        return;
                    }

                    let str = msg;

                    str = str.replace(/```(\w+)?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
                    str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');

                    tinymce.activeEditor.setContent(str);
                }
            }
        };
        eventSource.onerror = function (e) {
            $btn.removeClass('button-progress').prop('disabled', false);
            console.log(e);
            eventSource.close();
        };

    });

    /* generate speech to text */
    $('#speech_to_text').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var action = 'speech_to_text';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    tinymce.activeEditor.setContent(response.text);
                    tinymce.activeEditor.focus();

                    tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody(), true);
                    tinyMCE.activeEditor.selection.collapse(false);

                    $('.simplebar-scroll-content').animate({
                        scrollTop: $("#content-focus").offset().top
                    }, 500);

                    animate_value('quick-speech-left', response.old_used_speech, response.current_used_speech, 1000)
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            }
        });
    });

    /* generate code */
    $('#ai_code').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var action = 'ai_code';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $("#content-focus").html(response.text);

                    $('.simplebar-scroll-content').animate({
                        scrollTop: $("#content-focus").offset().top
                    }, 500);
                    hljs.highlightAll();

                    animate_value('quick-words-left', response.old_used_words, response.current_used_words, 4000)
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            }
        });
    });

    /* save ai document */
    $('#ai_document_form').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var action = 'save_document';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $form.find('#post_id').val(response.id);
                    Snackbar.show({
                        text: response.message,
                        pos: 'bottom-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration: 3000,
                        textColor: '#fff',
                        backgroundColor: '#383838'
                    });
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            }
        });
    });

    /* ai images */
    $('#ai_images').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var action = 'generate_image';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $.each(response.posts, function(index, post) {
                        var hashtags = Array.isArray(post.hashtags) ? escape_html(post.hashtags.join(' ')) : '';
                        var design = post.design && typeof post.design === 'object' ? post.design : {};
                        var debug = post.debug && typeof post.debug === 'object' ? post.debug : {};
                        var captionText = String(post.caption || '') + (hashtags ? "\n\n" + String(post.hashtags.join(' ')) : '');
                        var infoHtml = '';
                        var reelVideoUrl = post.rendered_video || post.source_video || '';
                        var isReel = String(post.post_type || '') === 'reel' && !!reelVideoUrl;
                        var primaryDownloadUrl = isReel ? reelVideoUrl : post.preview_image;
                        var primaryDownloadLabel = isReel ? 'Download Reel' : 'Download Post';
                        var previewMediaHtml = isReel
                            ? '<video src="' + escape_html(reelVideoUrl) + '" autoplay muted loop playsinline controls preload="metadata"></video>'
                            : '<img src="' + escape_html(post.preview_image) + '" alt="' + escape_html(post.title) + '">';
                        var actionsHtml = '<div class="social-post-actions margin-top-15">' +
                            '<a href="' + escape_html(primaryDownloadUrl) + '" class="social-action-btn" download title="' + escape_html(primaryDownloadLabel) + '" aria-label="' + escape_html(primaryDownloadLabel) + '"><i class="fa fa-download"></i></a>' +
                            '<a href="#" class="social-action-btn download-caption" data-title="' + escape_html(post.title) + '" data-caption="' + escape_html(captionText) + '" title="Download Caption" aria-label="Download Caption"><i class="fa fa-file-text-o"></i></a>';

                        if (Array.isArray(post.reel_script) && post.reel_script.length) {
                            infoHtml += '<p class="margin-bottom-10"><strong>Reel Script:</strong> ' + escape_html(post.reel_script.join(' | ')) + '</p>';
                        }

                        if (post.asset_title) {
                            infoHtml += '<p class="margin-bottom-0"><strong>Asset:</strong> ' + escape_html(post.asset_title) + '</p>';
                        }
                        if (post.asset && post.asset.remote_provider) {
                            infoHtml += '<p class="margin-bottom-0"><strong>Asset Source:</strong> ' + escape_html(String(post.asset.remote_provider)) + '</p>';
                        }

                        if (design.headline_font_key || design.background_tone) {
                            infoHtml += '<p class="margin-bottom-10"><strong>Design:</strong> ' +
                                escape_html(String(design.headline_font_key || '')) +
                                (design.body_font_key ? ' / ' + escape_html(String(design.body_font_key)) : '') +
                                (design.headline_size ? ', ' + escape_html(String(design.headline_size)) + 'px' : '') +
                                (design.background_tone ? ', ' + escape_html(String(design.background_tone)) : '') +
                                '</p>';
                        }

                        if (debug.generation_source || (debug.openai && debug.openai.error)) {
                            infoHtml += '<details class="margin-bottom-10"><summary><strong>Debug</strong></summary><div class="margin-top-10">';
                            if (debug.generation_source) {
                                infoHtml += '<p class="margin-bottom-5"><strong>Copy Source:</strong> ' + escape_html(String(debug.generation_source)) + '</p>';
                            }
                            if (debug.openai && debug.openai.error) {
                                infoHtml += '<p class="margin-bottom-5"><strong>OpenAI Debug:</strong> ' + escape_html(String(debug.openai.attempt || '')) + ' - ' + escape_html(String(debug.openai.error)) + '</p>';
                            }
                            if (debug.render && debug.render.background && debug.render.background.remote_provider) {
                                infoHtml += '<p class="margin-bottom-5"><strong>Remote Provider:</strong> ' + escape_html(String(debug.render.background.remote_provider)) + '</p>';
                            }
                            if (debug.render && debug.render.background && typeof debug.render.background.remote_source_downloaded !== 'undefined') {
                                infoHtml += '<p class="margin-bottom-5"><strong>Remote Source Downloaded:</strong> ' + escape_html(debug.render.background.remote_source_downloaded ? 'yes' : 'no') + '</p>';
                            }
                            if (debug.render && debug.render.background && debug.render.background.remote_source_path) {
                                infoHtml += '<p class="margin-bottom-5"><strong>Remote Source Path:</strong> ' + escape_html(String(debug.render.background.remote_source_path)) + '</p>';
                            }
                            infoHtml += '</div></details>';
                        }

                        if (reelVideoUrl) {
                            actionsHtml += '<a href="' + escape_html(reelVideoUrl) + '" class="social-action-btn" target="_blank" title="Open Reel Video" aria-label="Open Reel Video"><i class="fa fa-play"></i></a>';
                            actionsHtml += '<a href="' + escape_html(reelVideoUrl) + '" class="social-action-btn" download title="Download Reel Video" aria-label="Download Reel Video"><i class="fa fa-film"></i></a>';
                            actionsHtml += '<a href="' + escape_html(post.preview_image) + '" class="social-action-btn" download title="Download Cover" aria-label="Download Cover"><i class="fa fa-image"></i></a>';
                        }

                        actionsHtml += '<a href="#" class="social-action-btn social-share-btn" title="Share" aria-label="Share"><i class="fa fa-share-alt"></i></a>';
                        actionsHtml += '<a href="#" class="social-action-btn social-action-danger quick-delete" data-id="' + escape_html(String(post.id)) + '" data-action="delete_image" title="Delete" aria-label="Delete"><i class="fa fa-trash-o"></i></a></div>';

                        $("#generated_images_wrapper").prepend(
                            '<div class="col-xl-4 col-md-6 margin-bottom-30">' +
                                '<div class="dashboard-box social-post-card margin-top-0">' +
                                    '<div class="content">' +
                                        '<div class="social-post-preview">' + previewMediaHtml + '</div>' +
                                        '<div class="social-post-body with-padding">' +
                                            '<span class="dashboard-status-button yellow">' + escape_html(post.post_type.charAt(0).toUpperCase() + post.post_type.slice(1)) + '</span>' +
                                            '<h4 class="margin-top-15">' + escape_html(post.title) + '</h4>' +
                                            '<p class="margin-bottom-10"><strong>Overlay:</strong> ' + escape_html(post.overlay_text) + '</p>' +
                                            '<p class="margin-bottom-10"><strong>Caption:</strong> ' + escape_html(post.caption) + '</p>' +
                                            '<p class="margin-bottom-10"><strong>CTA:</strong> ' + escape_html(post.cta) + '</p>' +
                                            '<p class="margin-bottom-10"><strong>Hashtags:</strong> ' + hashtags + '</p>' +
                                            infoHtml +
                                            actionsHtml +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>'
                        );
                    });

                    animate_value('quick-images-left', response.old_used_images, response.current_used_images, 1000)

                    $('.simplebar-scroll-content').animate({
                        scrollTop: $("#generated_images_wrapper").offset().top
                    }, 500);
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            }
        });
    });

    /* ai images */
    $('#ai_text_speech').on('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var action = 'text_to_speech';
        var data = new FormData(this),
            $form = $(this);

        var $btn = $(this).find('.button'),
            $error = $(this).find('.form-error');
        $btn.addClass('button-progress').prop('disabled', true);

        $error.slideUp();
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    location.reload();
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            }
        });
    });

    /* delete ajax */
    $(document).on('click', '.quick-delete', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var action = $btn.data('action');

        if(confirm(LANG_ARE_YOU_SURE)) {
            $btn.addClass('button-progress').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: ajaxurl + '?action=' + action,
                data: {
                    'id': $btn.data('id')
                },
                dataType: 'json',
                success: function (response) {
                    $btn.removeClass('button-progress').prop('disabled', false);
                    if (response.success) {
                        var $container = $btn.closest('tr, .col-xl-4, .col-md-6, .col-sm-4, .col-md-2, .col-6');
                        if (!$container.length) {
                            $container = $btn.closest('.dashboard-box');
                        }
                        $container.fadeOut("slow", function(){
                            $(this).remove();
                        });

                        Snackbar.show({
                            text: response.message,
                            pos: 'bottom-center',
                            showAction: false,
                            actionText: "Dismiss",
                            duration: 3000,
                            textColor: '#fff',
                            backgroundColor: '#383838'
                        });
                    }
                }
            });
        }
    });

    $(document).on('click', '.download-caption', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var title = $btn.data('title') || 'caption';
        var caption = $btn.data('caption') || '';
        download_text_file(slugify_filename(title) + '-caption.txt', caption);
    });

    $(document).on('click', '.social-share-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        Snackbar.show({
            text: 'Share directly to your account (for paid members only)',
            pos: 'bottom-center',
            showAction: false,
            actionText: 'Dismiss',
            duration: 3000,
            textColor: '#fff',
            backgroundColor: '#383838'
        });
    });

    function animate_value(id, start, end, duration) {
        start = parseInt(start);
        end = parseInt(end);
        if (start === end) return;
        var range = end - start;
        var current = parseInt(start);
        var increment = end > start? 1 : -1;
        var stepTime = Math.abs(Math.floor(duration / range));
        var obj = document.getElementById(id);
        var timer = setInterval(function() {
            current += increment;
            obj.innerHTML = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
});
