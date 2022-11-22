jQuery(document).ready(function ($) {
    // user page agent label assign
    $("select.bbp_agent_labels").each(function () {
        $(this).on('change', () => {
            let label = $(this).find('option:selected').val();
            let user_id = $(this).attr('data-user_id');
            let security = $(this).attr('data-security');
            if (label && user_id && security) {
                let parent = $(this).parent();
                parent.append('<span class="message">Saving...</span>');
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'assign_agent_label',
                        label: label,
                        user_id: user_id,
                        security: security
                    },
                    cache: false,
                    success: function (html) {
                        parent.find('span.message').remove();
                        parent.append('<span class="message">' + html + '</span>');
                        setTimeout(function () {
                            parent.find('span.message').remove();
                        }, 2000);
                    }
                });
            }
        })
    });
})

