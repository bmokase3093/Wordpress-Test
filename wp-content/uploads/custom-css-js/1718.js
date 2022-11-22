<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
  jQuery(".finish_course").click(function(){
    console.log('hello user')
      window.open('/feedback-form/','_blank');

    });
console.log('hello ')
jQuery(document).on('click', '.finish_course', function (e) {
    e.preventDefault();
    window.open('/feedback-form/','_blank');

});</script>
<!-- end Simple Custom CSS and JS -->
