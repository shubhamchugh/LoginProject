<a href="#" id="scroll" style="display: inline;"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
<!-- jquery -->
<script src="{{ asset('themes/civic/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('themes/civic/js/popper.min.js') }}"></script>
<script src="{{ asset('themes/civic/js/bootstrap.min.js') }}"></script>

<script>
    $(document).ready(function () {
      $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
          $('#scroll').fadeIn();
        } else {
          $('#scroll').fadeOut();
        }
      });
      $('#scroll').click(function () {
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
      });
    });
</script>