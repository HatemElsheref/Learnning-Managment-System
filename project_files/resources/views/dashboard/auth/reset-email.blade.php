@component('mail::message')
    # Reset Your Account Password

    Please Click In Button To Or Copy Link Below To Reset Your Account Password

    <a href="{{$URL}}">copy this link</a>
    @component('mail::button', ['url' => $URL])
        Button Text
    @endcomponent

    Thanks,
    Application Admin
@endcomponent
