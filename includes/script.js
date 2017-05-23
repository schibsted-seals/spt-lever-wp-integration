(function ($) {
  jQuery('.faq-title').click(function () {
    jQuery(this).next().slideToggle();
  });


  window.openSchibstedPlModal = function (id, job) {
    var modal = document.getElementById(id);
    var close = document.getElementsByClassName('close')[0];
    var submitBtn = document.querySelector('.submit-btn');

    if (job) {
      ms.setValue([job]);
    }

    modal.style.display = 'flex';
    document.documentElement.style.overflow = 'hidden';


    close.onclick = function () {
      modal.style.display = 'none';
      document.documentElement.style.overflow = 'auto';
      window.removeEventListener('click', function () {
      });
      submitBtn.removeEventListener('click', function () {
      })
    };

    window.addEventListener('click', function (event) {
      if (event.target == modal) {
        document.documentElement.style.overflow = 'auto';
        modal.style.display = 'none';
      }
    });

    function changeValue(inputVal, valuesArray) {
      var value = '';
      if (valuesArray.length == 0) {
        inputVal.value = '';
      } else {
        valuesArray.forEach(function (val, key) {
          value = (value ? value + ", " + val : val);
        })
      }
      inputVal.value = value;
      console.log(value)
    }


  }
})(jQuery);
