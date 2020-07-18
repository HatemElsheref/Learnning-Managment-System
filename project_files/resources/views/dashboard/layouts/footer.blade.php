

<div class="right-sidebar-2">
    <div class="right-sidebar-container-2">
        <div class="slim-scroll-right-sidebar-2">

            <div class="right-sidebar-2-header">
                <h2>@lang('dashboard.layout_setting')</h2>
                <p>@lang('dashboard.ui_setting')</p>
                <div class="btn-close-right-sidebar-2">
                    <i class="mdi mdi-window-close"></i>
                </div>
            </div>

            <div class="right-sidebar-2-body">
                <span class="right-sidebar-2-subtitle">@lang('dashboard.header_layout')</span>
                <div class="no-col-space">
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 header-fixed-to btn-right-sidebar-2-active">Fixed</a>
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 header-static-to">Static</a>
                </div>

                <span class="right-sidebar-2-subtitle">@lang('dashboard.sidebar_layout')</span>
                <div class="no-col-space">
                    <select class="right-sidebar-2-select" id="sidebar-option-select">
                        <option value="sidebar-fixed">Fixed Default</option>
                        <option value="sidebar-fixed-minified">Fixed Minified</option>
                        <option value="sidebar-fixed-offcanvas">Fixed Offcanvas</option>
                        <option value="sidebar-static">Static Default</option>
                        <option value="sidebar-static-minified">Static Minified</option>
                        <option value="sidebar-static-offcanvas">Static Offcanvas</option>
                    </select>
                </div>

                <span class="right-sidebar-2-subtitle">@lang('dashboard.header_background')</span>
                <div class="no-col-space">
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active header-light-to">@lang('dashboard.light')</a>
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 header-dark-to">@lang('dashboard.dark')</a>
                </div>

                <span class="right-sidebar-2-subtitle">@lang('dashboard.navigation_background')</span>
                <div class="no-col-space">
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active sidebar-dark-to">@lang('dashboard.dark')</a>
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 sidebar-light-to">@lang('dashboard.light')</a>
                </div>

                <span class="right-sidebar-2-subtitle">@lang('dashboard.direction')</span>
                <div class="no-col-space">
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active ltr-to">@lang('dashboard.ltr')</a>
                    <a href="javascript:void(0);" class="btn-right-sidebar-2 rtl-to">@lang('dashboard.rtl')</a>
                </div>

                <div class="d-flex justify-content-center" style="padding-top: 30px">
                    <div id="reset-options" style="width: auto; cursor: pointer" class="btn-right-sidebar-2 btn-reset">
                        @lang('dashboard.reset_setting')</div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>
<footer class="footer mt-auto">
    <div class="copyright bg-white">
        <p>
            &copy; <span id="copy-year">2019</span> Copyright Reserved by
            <a
                class="text-primary"
                href="http://www.facebook.com/hatem.elsheref.73"
                target="_blank"
            >ELSHEREF</a
            >.
        </p>
    </div>
    <script>
        var d = new Date();
        var year = d.getFullYear();
        document.getElementById("copy-year").innerHTML = year;
    </script>
</footer>
</div>
</div>
<script src="{{Dashboard_Assets()}}/plugins/jquery/jquery.min.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/slimscrollbar/jquery.slimscroll.min.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/jekyll-search.min.js"></script>
<script src="{{asset('dashboard/assets/plugins/charts/Chart.min.js')}}"></script>
<script src="{{Dashboard_Assets()}}/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/daterangepicker/moment.min.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/daterangepicker/daterangepicker.js"></script>
<script src="{{Dashboard_Assets()}}/plugins/select2/js/select2.min.js"></script>
<script src="{{Dashboard_Assets()}}/js/drobzone.js"></script>
{{--<script src="{{Dashboard_Assets()}}/plugins/toastr/toastr.min.js"></script>--}}
<script src="{{Dashboard_Assets()}}/js/sleek.bundle.js"></script>

{{--@include('dashboard.layouts.message')--}}

<script src="{{Dashboard_Assets()}}/js/sweet_alert.js"></script>

    <script>
        function RemoveElement(FormId){
            swal("Are you sure you want to delete this?", {
                buttons: ["Cancel", true],
            }).then(function(value) {
                if (value) {
                    $('#'+FormId).submit();
                }
            });
        }
        function RemovesElement(FormId){
            swal('Are you sure you want to Restore this?', {
                buttons: ["Cancel", true],
            }).then(function(value) {
                if (value) {
                    $('#'+FormId).submit();
                    swal("Good job!", "You clicked the button!", "success");


                }
            });
        }
        function selectOrUnselectAll() {
            let state=$('#state').val();
            if (state==="off"){
                $('.user-select').each(function(index, value) {
                    $(this).prop("checked", true);
                });
                $('#state').val("on");
            }else{
                $('.user-select').each(function(index, value) {
                    $(this).prop("checked", false);
                });
                $('#state').val("off");
            }


        }
        function selectOrUnselectAllPhotos() {
            let state=$('#state_photo').val();
            if (state==="off"){
                $('.photo-select').each(function(index, value) {
                    $(this).prop("checked", true);
                });
                $('#state_photo').val("on");
            }else{
                $('.photo-select').each(function(index, value) {
                    $(this).prop("checked", false);
                });
                $('#state_photo').val("off");
            }


        }
        function selectOrUnselectAllExams() {
            let state=$('#state_exam').val();
            if (state==="off"){
                $('.exam-select').each(function(index, value) {
                    $(this).prop("checked", true);
                });
                $('#state_exam').val("on");
            }else{
                $('.exam-select').each(function(index, value) {
                    $(this).prop("checked", false);
                });
                $('#state_exam').val("off");
            }


        }
        function SweetAlert(message) {
            swal(message, {
                buttons:{ confirm: {
                        text: "Ok",
                        visible: true,
                        closeModal: true,
                    }
                }
            });
        }
        function OpenMedia(modal){
            $('video').each(function(index, value) {
                $('video').get(index).pause();
            });
           document.getElementById('video-'+modal).play();
            $('#'+modal).modal('show');

        }
        function closeModel(){
            $('video').each(function(index, value) {
                $('video').get(index).pause();
            });

        }






        // $('.stop-video').on('hide.bs.modal', function(e) {
        //     $('.yt-frame', this).each(function() {
        //         this.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*');
        //     });
        // });
    </script>


@stack('scripts')



</body>
</html>

