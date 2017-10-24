document.addEventListener('wpcf7mailsent', function (event) {
  var trigger = jQuery(jQuery.fn.popmake.last_open_trigger);
  var fd = new FormData();
  var file = jQuery(document).find('input[type="file"]');
  var url = trigger.attr('href');
  var individual_file = file[0].files[0];
  fd.append("file", individual_file);
  fd.append('action', 'my_action');
  fd.append('url', url);
  fd.append('name', jQuery('[name="full-name"]').val());
  fd.append('email', jQuery('[name="your-email"]').val());
  fd.append('phone', jQuery('[name="phone"]').val());
  fd.append('org', jQuery('[name="company"]').val());
  fd.append('LinkedIn', jQuery('[name="linkedin"]').val());
  fd.append('Github', jQuery('[name="github"]').val());
  fd.append('Twitter', jQuery('[name="twitter"]').val());
  fd.append('Other', jQuery('[name="other"]').val());
  fd.append('Portfolio', jQuery('[name="portfolio"]').val());
  fd.append('comments', jQuery('[name="additional"]').val());
  fd.append('source', 'jobs.schisbted.com site');
  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    data: fd,
    contentType: false,
    processData: false
  });
}, false);
