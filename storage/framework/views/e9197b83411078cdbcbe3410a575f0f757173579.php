<script type="application/ld+json">
    {
        "@context":"http://schema.org",
        "@type":"EmailMessage",
        "description":"Confirm your Invoice Ninja account",
        "action":
        {
            "@type":"ConfirmAction",
            "name":"Confirm account",
            "handler": {
                "@type": "HttpActionHandler",
                "url": "<?php echo e(URL::to("user/confirm/{$user->confirmation_code}")); ?>"
            },
            "publisher": {
                "@type": "Organization",
                "name": "Invoice Ninja",
                "url": "<?php echo e(NINJA_WEB_URL); ?>"
            }
        }
    }
</script>