<!-- resources/views/emails/newsletter-confirm.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Subscription</title>
</head>
<body>
    <h1>Confirm Your Subscription</h1>
    <p>Thanks for signing up! Please confirm your subscription by clicking the link below:</p>
    <p><a href="{{ route('newsletter.confirm', $signup->verification_token) }}">Confirm Subscription</a></p>



	<p class="text-sm text-gray-500 mt-6">
	    If you didn’t sign up or want to stop receiving emails, you can 
	    <a href="{{ route('newsletter.unsubscribe', $signup->verification_token) }}" class="text-blue-400 underline">
	        unsubscribe here
	    </a>.
	</p>

</body>
</html>
