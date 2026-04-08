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

    function set_generation_progress($form, value) {
        var safeValue = Math.max(0, Math.min(100, Math.round(value || 0)));
        $form.find('.social-generator-progress').stop(true, true).fadeIn(120);
        $form.find('.social-generator-progress-bar').css('width', safeValue + '%');
        $form.find('.social-generator-progress-text').text(safeValue + '%');
    }

    function reset_generation_progress($form) {
        $form.find('.social-generator-progress').stop(true, true).fadeOut(180, function () {
            $form.find('.social-generator-progress-bar').css('width', '0%');
            $form.find('.social-generator-progress-text').text('0%');
        });
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

    function social_post_caption_export(post) {
        var hashtags = Array.isArray(post.hashtags) ? post.hashtags.join(' ') : '';
        return String(post.caption || '') + (hashtags ? "\n\n" + hashtags : '');
    }

    function social_vote_class(currentVote, buttonVote) {
        return Number(currentVote || 0) === Number(buttonVote) ? ' is-active' : '';
    }

    function social_post_meta_html(post, context) {
        var html = '';
        var hashtags = Array.isArray(post.hashtags) ? escape_html(post.hashtags.join(' ')) : '';
        var design = post.design && typeof post.design === 'object' ? post.design : {};

        if (context === 'grid' && post.grid && post.grid.position) {
            html += '<p class="margin-bottom-10"><strong>Tile:</strong> ' + escape_html(String(post.grid.position)) + '</p>';
        }
        html += '<p class="margin-bottom-10"><strong>Caption:</strong> ' + escape_html(post.caption || '') + '</p>';
        if (post.cta) {
            html += '<p class="margin-bottom-10"><strong>CTA:</strong> ' + escape_html(post.cta) + '</p>';
        }
        if (hashtags) {
            html += '<p class="margin-bottom-10"><strong>Hashtags:</strong> ' + hashtags + '</p>';
        }
        if (Array.isArray(post.reel_script) && post.reel_script.length) {
            html += '<p class="margin-bottom-10"><strong>Reel Script:</strong> ' + escape_html(post.reel_script.join(' | ')) + '</p>';
        }
        if (post.asset_title) {
            html += '<p class="margin-bottom-0"><strong>Asset:</strong> ' + escape_html(post.asset_title) + '</p>';
        }
        if (post.asset && post.asset.remote_provider) {
            html += '<p class="margin-bottom-0"><strong>Asset Source:</strong> ' + escape_html(String(post.asset.remote_provider)) + '</p>';
        }
        if (post.asset && post.asset.remote_provider === 'unsplash' && post.asset.remote_author) {
            html += '<p class="margin-bottom-10"><small>Photo by ';
            if (post.asset.remote_author_url) {
                html += '<a href="' + escape_html(String(post.asset.remote_author_url)) + '" target="_blank" rel="nofollow noopener">' + escape_html(String(post.asset.remote_author)) + '</a>';
            } else {
                html += escape_html(String(post.asset.remote_author));
            }
            html += ' on ';
            if (post.asset.remote_page_url) {
                html += '<a href="' + escape_html(String(post.asset.remote_page_url)) + '" target="_blank" rel="nofollow noopener">Unsplash</a>';
            } else {
                html += 'Unsplash';
            }
            html += '</small></p>';
        }
        if (design.headline_font_key || design.background_tone) {
            html += '<p class="margin-bottom-10"><strong>Design:</strong> ' +
                escape_html(String(design.headline_font_key || '')) +
                (design.body_font_key ? ' / ' + escape_html(String(design.body_font_key)) : '') +
                (design.headline_size ? ', ' + escape_html(String(design.headline_size)) + 'px' : '') +
                (design.background_tone ? ', ' + escape_html(String(design.background_tone)) : '') +
                '</p>';
        }
        return html;
    }

    function render_social_post_card(post, options) {
        options = options || {};
        var context = options.context || 'campaign';
        var wrapperClass = options.wrapperClass || 'col-xl-4 col-md-6 margin-bottom-30';
        var hashtags = Array.isArray(post.hashtags) ? escape_html(post.hashtags.join(' ')) : '';
        var reelVideoUrl = post.rendered_video || post.source_video || '';
        var isReel = String(post.post_type || '') === 'reel' && !!reelVideoUrl;
        var primaryDownloadUrl = isReel ? reelVideoUrl : post.preview_image;
        var primaryDownloadLabel = isReel ? 'Download Reel' : 'Download Post';
        var previewMediaHtml = isReel
            ? '<video src="' + escape_html(reelVideoUrl) + '" autoplay muted loop playsinline controls preload="metadata"></video>'
            : '<img src="' + escape_html(post.preview_image) + '" alt="' + escape_html(post.title) + '">';
        var captionText = social_post_caption_export(post);
        var actionsHtml = '<div class="social-post-actions margin-top-15">' +
            '<button type="button" class="social-action-btn social-vote-btn' + social_vote_class(post.vote_value, 1) + '" data-id="' + escape_html(String(post.id)) + '" data-vote="1" title="Thumbs up" aria-label="Thumbs up"><i class="fa fa-thumbs-up"></i></button>' +
            '<button type="button" class="social-action-btn social-vote-btn' + social_vote_class(post.vote_value, -1) + '" data-id="' + escape_html(String(post.id)) + '" data-vote="-1" title="Thumbs down and regenerate" aria-label="Thumbs down and regenerate"><i class="fa fa-thumbs-down"></i></button>' +
            '<button type="button" class="social-action-btn social-regenerate-btn" data-id="' + escape_html(String(post.id)) + '" title="Regenerate image" aria-label="Regenerate image"><i class="fa fa-refresh"></i></button>' +
            '<a href="' + escape_html(primaryDownloadUrl) + '" class="social-action-btn" download title="' + escape_html(primaryDownloadLabel) + '" aria-label="' + escape_html(primaryDownloadLabel) + '"><i class="fa fa-download"></i></a>' +
            '<a href="#" class="social-action-btn download-caption" data-title="' + escape_html(post.title) + '" data-caption="' + escape_html(captionText) + '" title="Download Caption" aria-label="Download Caption"><i class="fa fa-file-text-o"></i></a>';

        if (reelVideoUrl) {
            actionsHtml += '<a href="' + escape_html(reelVideoUrl) + '" class="social-action-btn" target="_blank" title="Open Reel Video" aria-label="Open Reel Video"><i class="fa fa-play"></i></a>';
            actionsHtml += '<a href="' + escape_html(reelVideoUrl) + '" class="social-action-btn" download title="Download Reel Video" aria-label="Download Reel Video"><i class="fa fa-film"></i></a>';
            actionsHtml += '<a href="' + escape_html(post.preview_image) + '" class="social-action-btn" download title="Download Cover" aria-label="Download Cover"><i class="fa fa-image"></i></a>';
        }

        actionsHtml += '<a href="#" class="social-action-btn social-share-btn" title="Share" aria-label="Share"><i class="fa fa-share-alt"></i></a>';
        actionsHtml += '<a href="#" class="social-action-btn social-action-danger quick-delete" data-id="' + escape_html(String(post.id)) + '" data-action="delete_image" title="Delete" aria-label="Delete"><i class="fa fa-trash-o"></i></a></div>';

        return '<div class="' + wrapperClass + ' social-post-card-slot" data-post-id="' + escape_html(String(post.id)) + '" data-social-context="' + escape_html(context) + '">' +
            '<div class="dashboard-box social-post-card margin-top-0">' +
                '<div class="content">' +
                    '<div class="social-post-preview">' + previewMediaHtml + '</div>' +
                    '<div class="social-post-body with-padding">' +
                        '<span class="dashboard-status-button yellow">' + escape_html(String(post.post_type || '').charAt(0).toUpperCase() + String(post.post_type || '').slice(1)) + '</span>' +
                        '<h4 class="margin-top-15">' + escape_html(post.title) + '</h4>' +
                        '<div class="social-overlay-editor margin-bottom-15">' +
                            '<label class="social-overlay-label" for="social-overlay-' + escape_html(String(post.id)) + '"><strong>Overlay:</strong></label>' +
                            '<textarea id="social-overlay-' + escape_html(String(post.id)) + '" class="with-border social-overlay-input" rows="2" data-id="' + escape_html(String(post.id)) + '">' + escape_html(post.overlay_text || '') + '</textarea>' +
                            '<div class="social-overlay-actions"><button type="button" class="button small ripple-effect social-overlay-save-btn" data-id="' + escape_html(String(post.id)) + '">Save overlay</button></div>' +
                        '</div>' +
                        social_post_meta_html(post, context) +
                        actionsHtml +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    }

    function replace_social_post_card(post, $origin) {
        var $slot = $origin.closest('.social-post-card-slot');
        if (!$slot.length) {
            return;
        }
        var context = $slot.data('social-context') || 'campaign';
        var wrapperClass = $.trim(($slot.attr('class') || '').replace(/\bsocial-post-card-slot\b/g, ''));
        var newHtml = render_social_post_card(post, {
            context: context,
            wrapperClass: wrapperClass
        });
        $slot.replaceWith(newHtml);
        $(document).trigger('socialPostUpdated', [post, context]);
    }

    function show_social_message(text) {
        Snackbar.show({
            text: text,
            pos: 'bottom-center',
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: '#fff',
            backgroundColor: '#383838'
        });
    }

    window.render_social_post_card = render_social_post_card;

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
        set_generation_progress($form, 4);

        var progressValue = 4;
        var progressTimer = window.setInterval(function () {
            if (progressValue < 40) {
                progressValue += 2;
            } else if (progressValue < 68) {
                progressValue += 1;
            } else if (progressValue < 84) {
                progressValue += 1;
            }
            set_generation_progress($form, progressValue);
        }, 520);

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
                window.clearInterval(progressTimer);
                set_generation_progress($form, 100);
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success) {
                    $.each(response.posts, function(index, post) {
                        $("#generated_images_wrapper").prepend(render_social_post_card(post, {
                            context: 'campaign',
                            wrapperClass: 'col-xl-4 col-md-6 margin-bottom-30'
                        }));
                    });

                    animate_value('quick-images-left', response.old_used_images, response.current_used_images, 1000)

                    $('.simplebar-scroll-content').animate({
                        scrollTop: $("#generated_images_wrapper").offset().top
                    }, 500);
                    window.setTimeout(function () {
                        reset_generation_progress($form);
                    }, 500);
                } else {
                    reset_generation_progress($form);
                    $error.html(response.error).slideDown().focus();
                }
            },
            error: function () {
                window.clearInterval(progressTimer);
                $btn.removeClass('button-progress').prop('disabled', false);
                reset_generation_progress($form);
                $error.html('Unable to generate posts right now. Please try again.').slideDown().focus();
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
        show_social_message('Share directly to your account (for paid members only)');
    });

    $(document).on('click', '.social-regenerate-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.addClass('button-progress').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=regenerate_social_post',
            data: { id: $btn.data('id') },
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success && response.post) {
                    replace_social_post_card(response.post, $btn);
                    show_social_message(response.message || 'Image regenerated successfully.');
                } else {
                    show_social_message(response.error || 'Unable to regenerate this image right now.');
                }
            },
            error: function () {
                $btn.removeClass('button-progress').prop('disabled', false);
                show_social_message('Unable to regenerate this image right now.');
            }
        });
    });

    $(document).on('click', '.social-vote-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var vote = Number($btn.data('vote') || 0);
        var action = vote < 0 ? 'regenerate_social_post' : 'vote_social_post_image';
        var data = { id: $btn.data('id'), vote: vote };

        $btn.addClass('button-progress').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=' + action,
            data: data,
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success && response.post) {
                    replace_social_post_card(response.post, $btn);
                    show_social_message(response.message || 'Feedback saved.');
                } else {
                    show_social_message(response.error || 'Unable to save feedback right now.');
                }
            },
            error: function () {
                $btn.removeClass('button-progress').prop('disabled', false);
                show_social_message('Unable to save feedback right now.');
            }
        });
    });

    $(document).on('click', '.social-overlay-save-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var id = $btn.data('id');
        var $input = $('.social-overlay-input[data-id="' + id + '"]');
        $btn.addClass('button-progress').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: ajaxurl + '?action=save_social_post_overlay',
            data: {
                id: id,
                overlay_text: $input.val()
            },
            dataType: 'json',
            success: function (response) {
                $btn.removeClass('button-progress').prop('disabled', false);
                if (response.success && response.post) {
                    replace_social_post_card(response.post, $btn);
                    show_social_message(response.message || 'Overlay updated.');
                } else {
                    show_social_message(response.error || 'Unable to update the overlay right now.');
                }
            },
            error: function () {
                $btn.removeClass('button-progress').prop('disabled', false);
                show_social_message('Unable to update the overlay right now.');
            }
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
