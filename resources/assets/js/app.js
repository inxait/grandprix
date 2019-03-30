import $ from 'jquery';
import axios from 'axios';
import humane from 'humane-js';
import Vue from 'vue';
import StartTrivia from './components/StartTrivia.vue';
import { getJsonFormData, isDataEmpty, isValidEmail } from './utils';

const token = document.head.querySelector('meta[name="csrf-token"]');

window.$ = window.jQuery = $;
window.humane = humane;
require('bootstrap-sass');
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

humane.error = humane.spawn({
  addnCls: 'humane-flatty-error',
  timeout: 4000
});

//login
$('#login-form button[type="submit"]').click(function(e) {
  e.preventDefault();
  const data = getJsonFormData('#login-form');

  if (isDataEmpty(data)) {
    humane.error('Hay campos vacios');
  } else {
    axios.post('login', data)
    .then((res) => {
      if (res.data.success) {
        window.location.href = res.data.info;
      } else {
        const keys = Object.keys(res.data.info);
        keys.forEach((item) => {
          res.data.info[item].forEach((err) => {
            humane.error(err);
          });
        });
      }
    }).catch(() => {
      humane.error('Ha ocurrido un error al intentar ingresar');
    });
  }
});

//register
$('#register-form #departments-select').on('change', function() {
  const dept = $(this).val();
  const citiesSel = $('#register-form #cities-select');
  citiesSel.attr('disabled', 'disabled');

  axios.get(`departments/${dept}/cities`).then((res) => {
    let tmpl = '';
    res.data.info.forEach(function(city) {
      tmpl += `<option value="${city.id}">${city.name}</option>`;
    });
    citiesSel.removeAttr('disabled').html(tmpl);
  }).catch(() => {
    humane.error('Ha ocurrido un error al cargar las ciudades');
  });
});

$('#register-form button[type="submit"]').click(function(e) {
  e.preventDefault();
  const data = getJsonFormData('#register-form');

  if (isDataEmpty(data)) {
    humane.error('Hay campos vacios');
  } else if (!isValidEmail(data.email)) {
    humane.error('El correo ingresado no es un correo válido');
  } else {
    axios.post('register', data)
    .then((res) => {
      if (res.data.success) {
        $('#register-col').addClass('hidden');
        $('.page.auth .alert').removeClass('hidden');
      } else {
        res.data.errors.forEach(item => {
          humane.error(item);
        });
      }
    }).catch(() => {
      humane.error('Ha ocurrido un error al intentar registrarte');
    });
  }
});

//recover-account
$('#recover-form button[type="submit"]').click(function(e) {
  e.preventDefault();
  const self = $(this);
  const data = getJsonFormData('#recover-form');

  if (isDataEmpty(data)) {
    humane.error('Hay campos vacios');
  } else if (!isValidEmail(data.email)) {
    humane.error('El correo ingresado no es un correo válido');
  } else {
    self.attr('disabled', 'disabled');
    axios.post('password/email', data)
    .then((res) => {
      if (res.data.success) {
        humane.info = humane.spawn({
          addnCls: 'humane-flatty-success',
          timeout: 3500
        });

        humane.info('Se ha enviado un correo con la información para recuperar la cuenta');
      } else {
        humane.error(res.data.info);
      }
      setTimeout(function() {
        self.removeAttr('disabled');
      }, 3000);
    }).catch(() => {
      humane.error('Ha ocurrido un error al intentar recuperar la cuenta');
    });
  }
});

//activate-account
$('.select-radio').click(function () {
  const val = $(this).val();
  const name = $(this).attr('name');
  const folder = name.split('_')[0];
  let baseURL = 'images/avatar/';
  $(`.character .${folder} img`).attr('src', `${baseURL}${folder}/${val}.png`);
});

//trivias
$('button#btn-trivia').click(function() {
  $('.trivia-main').addClass('hidden');
  $('.trivia-game').removeClass('hidden');

  Vue.component('trivia-test', StartTrivia);
  new Vue({
    el: '#start-trivia'
  });
});

$('.btn-period').click(function(e) {
  const period = $(this).data('period');

  axios.get(`period/${period}/ranking`)
    .then((res) => {
      if (res.data.success) {
        $('#main-ranking').html(getRankingTable(res.data.info, period));
      }
    }).catch(() => {
      humane.error(`Ha ocurrido un al cargar el ranking del trimestre ${period}`);
    });
});

function getRankingTable(ranking, period) {
  let tmpl = `<div class="title">RANKING TRIMESTRE ${period} GRAND PRIX</div>
                <table class="table">
                    <tbody>`;
  ranking.forEach(function(user, idx) {
    tmpl += `<tr>
                <td>
                    <img src="/images/avatar/helmet/${user.avatar}.png" class="img-responsive">
                </td>
                <td>${idx + 1}.</td>
                <td class="name">
                    <p class="surname">${user.surname.toUpperCase()}</p>
                    <p class="username">${user.first_name} ${user.last_name}</p>
                </td>
                <td class="km">${user.total_points} KM</td>
            </tr>`;
  });

  tmpl +=  `</tbody>
        </table>`;

  return tmpl;
}
