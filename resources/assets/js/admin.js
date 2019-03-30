import $ from 'jquery';
import axios from 'axios';
import datepicker from 'bootstrap-datepicker';
import addDays from 'date-fns/add_days'
import humane from 'humane-js';
import numeral from 'numeral';
import Vue from 'vue';
import CreateTrivia from './components/CreateTrivia.vue';
import { getJsonFormData, isDataEmpty, isValidEmail } from './utils';

const token = document.head.querySelector('meta[name="csrf-token"]');

window.$ = window.jQuery = $;
const url = window.location.href;
window.humane = humane;
require('bootstrap-sass');
require('multiselect');
require('./i18n-es.js');
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

humane.error = humane.spawn({
  addnCls: 'humane-flatty-error',
  timeout: 4000
});

if (url.indexOf('/trivias/crear') !== -1) {
  Vue.component('create-trivia', CreateTrivia);
  new Vue({
    el: '#admin-trivia'
  });
}

$('#update-account-form #departments-select').on('change', function () {
  const dept = $(this).val();
  const citiesSel = $('#update-account-form #cities-select');
  citiesSel.attr('disabled', 'disabled');

  axios.get(`/departments/${dept}/cities`).then((res) => {
    let tmpl = '';
    res.data.info.forEach(function (city) {
      tmpl += `<option value="${city.id}">${city.name}</option>`;
    });
    citiesSel.removeAttr('disabled').html(tmpl);
  }).catch(() => {
    humane.error('Ha ocurrido un error al cargar las ciudades');
  });
});

$('.multiselect').multiSelect();

$('.link-confirmation').click(function (e) {
  e.preventDefault();
  const href = $(this).data('link');
  console.log(href);
  $('#confirmModal button.continue').click(function () {
    window.location.href = href;
  });
});

$('.link-approve').click(function (e) {
  e.preventDefault();
  const href = $(this).data('link');
  console.log(href);
  $('#approveModal button.continue').click(function () {
    $(this).attr('disabled', 'disabled');
    window.location.href = href;
  });
});

const today = new Date();
const tomorrow = addDays(today, 1);

$('.date').datepicker({
  autoclose: false,
  format: 'dd/mm/yyyy',
  startView: 'decade',
  language: 'es'
});

$('.start-date').datepicker({
  autoclose: true,
  format: 'dd/mm/yyyy',
  startDate: 'today',
  language: 'es'
});

$('.finish-date').datepicker({
  autoclose: true,
  format: 'dd/mm/yyyy',
  startDate: tomorrow,
  language: 'es'
});

$('.amount').on('keyup', function () {
  let val = $(this).val();
  if (val) {
    let number = numeral(val);
    $(this).val(number.format('0,0'));
  }
});

$('#measure-select').on('change', function () {
  const units = $(this).find('option:selected').data('units');
  $('.measure-addon').html(units);
});

$('#create-fulfillment-form button[type="submit"]').click((e) => {
  e.preventDefault();
    //remove format
    $('.amount').each(function (idx, item) {
      $(item).val(numeral($(item).val()).value());
    });

    $('#create-fulfillment-form').submit();
  });

$('.liquidation-link').click(function (e) {
  $('.alert').removeClass('hidden').html('Creando liquidación, esta operación puede tardar varios minutos.');
});

$('.radio-points').on('change', function () {
  const val = $(this).val();
  const totalInput = $('#trivia-total-input');

  if (val == 'no') {
    totalInput.removeClass('hidden');
  } else {
    totalInput.addClass('hidden');
  }
});

$('button#create-report').click(function () {
  const form = $(this).closest('form');
  form.find('.progress').removeClass('hidden');
});
