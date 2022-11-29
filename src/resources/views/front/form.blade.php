@if ($errors ?? false)
    @foreach ($errors->all() as $error)
        <div style="color:red">
            {{ $error }}<br>
        </div>
    @endforeach

    <br>
@endif
<form id="robots-txt-form"
      action="/debug/robots-txt"
      method="POST">
    @csrf

    <label for="input1">Label</label>

    <input id="input1"
           name="input1"
           type="text">

    @if ($TwillRobotsTxt['enabled'])
        <input id="g-recaptcha-response"
               name="g-recaptcha-response"
               type="hidden">

        <button type="button"
                onclick="return onSubmitClick();">
            Submit
        </button>
    @else
        <button type="button">Submit</button>
    @endif

    <br>

    <div>Site key: {{ $TwillRobotsTxt['keys']['username'] }}</div>
</form>

@if ($TwillRobotsTxt['enabled'])
    <script src="{{ $TwillRobotsTxt['asset'] }}"></script>

    <script>
        console.log('Robots TXT 3 loaded');

        function onSubmitClick(e) {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $TwillRobotsTxt['keys']['username'] }}', {
                    action: 'submit'
                }).then(function(token) {
                    document.getElementById("g-recaptcha-response").value = token;
                    document.getElementById("robots-txt-form").submit();
                });
            });
        }
    </script>
@endif
