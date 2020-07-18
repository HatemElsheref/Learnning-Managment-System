
<script type="text/javascript">
        RTLDirection="toast-top-left";
        LTRDirection="toast-top-right";
        CurrentDirection="";
        var toaster = $('#toaster1');

        function callToasterWarning(positionClass,Message="",Title="") {
            toastr.options = {
                closeButton: false,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: positionClass,
                preventDuplicates: false,
                onclick: null,
                showDuration: "3000",
                hideDuration: "3000",
                timeOut: "5000",
                extendedTimeOut: "3000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.warning(Message,Title);
        }
        function callToasterInfo(positionClass,Message="",Title="") {
            toastr.options = {
                closeButton: false,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: positionClass,
                preventDuplicates: false,
                onclick: null,
                showDuration: "3000",
                hideDuration: "3000",
                timeOut: "5000",
                extendedTimeOut: "3000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.info(Message,Title);
        }
        function callToasterErrors(positionClass,Message="",Title="") {
            toastr.options = {
                closeButton: false,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: positionClass,
                preventDuplicates: false,
                onclick: null,
                showDuration: "3000",
                hideDuration: "3000",
                timeOut: "5000",
                extendedTimeOut: "3000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.error(Message,Title);
        }
        function callToasterSuccess(positionClass,Message="",Title="") {
            toastr.options = {
                closeButton: false,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: positionClass,
                preventDuplicates: false,
                onclick: null,
                showDuration: "3000",
                hideDuration: "3000",
                timeOut: "5000",
                extendedTimeOut: "3000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.success(Message,Title);
        }
        function Success(Direction,Message,Title) {
            callToasterSuccess(Direction,Message,Title);
        }
        function Error(Direction,Message,Title) {
            callToasterErrors(Direction,Message,Title);
        }
        function Failure(Direction,Message,Title) {
            callToasterErrors(Direction,Message,Title);
        }
        function Info(Direction,Message,Title) {
            callToasterInfo(Direction,Message,Title);
        }
        function Warnning(Direction,Message,Title) {
            callToasterWarning(Direction,Message,Title);
        }
</script>

    @if(session()->has('Notification_Msg'))
        @if(session()->get('Notification_Direction')=='ar')
        <script type="text/javascript">CurrentDirection=RTLDirection;</script>
        @else
        <script type="text/javascript">CurrentDirection=LTRDirection;</script>
        @endif
        @switch(session()->get('Notification_Type'))
            @case('success')
            <script type="text/javascript">
                 Success(CurrentDirection,'{{session()->get("Notification_Msg")}}','{{session()->get("Notification_Title")}}');
            </script>
            @break
            @case('fail')
            <script type="text/javascript">
                Failure(CurrentDirection,'{{session()->get("Notification_Msg")}}','{{session()->get("Notification_Title")}}');
            </script>
            @break
            @case('error')
            <script type="text/javascript">
                Error(CurrentDirection,'{{session()->get("Notification_Msg")}}','{{session()->get("Notification_Title")}}');
            </script>
            @break
            @case('info')
            <script type="text/javascript">
                Info(CurrentDirection,'{{session()->get("Notification_Msg")}}','{{session()->get("Notification_Title")}}');
            </script>
            @break
            @case('warning')
            <script type="text/javascript">
                Warnning(CurrentDirection,'{{session()->get("Notification_Msg")}}','{{session()->get("Notification_Title")}}');
            </script>
            @break
         @endswitch

        @endif

@if(app()->getLocale()=='ar')
    <script type="text/javascript">
        var ltr = jQuery('.ltr-to');
        var rtl = jQuery('.rtl-to');
        rtl.addClass('btn-right-sidebar-2-active');
        ltr.removeClass('btn-right-sidebar-2-active');
    </script>
@else
    <script type="text/javascript">
        var ltr = jQuery('.ltr-to');
        var rtl = jQuery('.rtl-to');
        ltr.addClass('btn-right-sidebar-2-active');
        rtl.removeClass('btn-right-sidebar-2-active');
    </script>
@endif
