"use strict";

jQuery(document).ready(function ($) {
  var labels = {};

  var IsJsonString = function IsJsonString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }

    return true;
  };

  $('.repeatable_label').each(function (i, element) {
    var value = $(element).parent().find('input[type="hidden"]').val();
    var fi = $(element).attr('data-field');

    if (typeof window[fi] !== 'undefined') {
      labels[fi] = window[fi];
      setTimeout(function () {
        $('.repeatable_label').trigger('updated_repeatable_label', {
          field: fi
        });
      }, 100);
      $(element).parent().find('input[type="hidden"]').val(JSON.stringify(labels[fi]));
    }
  }); //push element

  $('.repeatable_label').on('click', function (e) {
    var cpt = e.target.getAttribute('data-cpt');
    var type = e.target.getAttribute('data-type');
    var field = e.target.getAttribute('data-field');

    if (type == 'label') {
      if (!labels.hasOwnProperty(field) || !labels || !labels[field]) {
        labels[field] = [];
      }

      labels[field].push({
        'label': '',
        'color': ''
      });
      $('.repeatable_label').trigger('updated_repeatable_label', {
        field: field
      });
    }
  }); // generate html 

  $('.repeatable_label').on('updated_repeatable_label', function (e, data) {
    var field = data.field;
    var html = '';

    if (labels && labels[field]) {
      labels[field].map(function (item, i) {
        html += '<li><input type="text" data-type="label"  data-index="' + i + '" value="' + item.label + '" /><input type="color" data-type="color" data-index="' + i + '"  value="' + item.color + '" /><span data-index="' + i + '"class="remove_label">&times;</span></li>';
      });
      $('#' + field).html(html);
    } // input change handle


    $('#' + field + ' input').on('change', function () {
      var index = $(this).attr('data-index');
      var type = $(this).attr('type');

      if (type == 'text') {
        labels[field][index]['label'] = $(this).val();
      } else if (type == 'color') {
        labels[field][index]['color'] = $(this).val();
      }

      $('.repeatable_label').trigger('updated_repeatable_label', {
        field: field
      });
    });
    $('#' + field + ' .remove_label').on('click', function () {
      var index = $(this).attr('data-index');
      labels[field].splice(index, 1);
      $('.repeatable_label').trigger('updated_repeatable_label', {
        field: field
      });
    });
    $('input[type="hidden"]input[name="' + field + '"]').val(JSON.stringify(labels[field]));
  });
}, false);