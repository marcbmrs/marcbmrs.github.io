(function () {
  'use strict';

  var WHATSAPP = '5551995481422';
  var TIME_ZONE = 'America/Sao_Paulo';

  // Horários em minutos desde 00:00. Índice = dia da semana (0 = domingo).
  var HOURS = [
    [],
    [[540, 1140]],
    [[540, 1140]],
    [[540, 1140]],
    [[540, 1140]],
    [[540, 1140]],
    [[540, 720], [840, 1140]]
  ];

  var DAY_NAMES = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];

  // Abertura da mensagem e exemplo do campo de detalhes para cada motivo de compra.
  var INTENTS = {
    festa: {
      opening: 'Olá, Megamix! Vim pelo site. Gostaria de consultar estes itens para uma festa:',
      placeholder: 'Ex.: tema, data e para quantas pessoas'
    },
    producao: {
      opening: 'Olá, Megamix! Vim pelo site. Gostaria de consultar estes itens para a minha produção de doces:',
      placeholder: 'Ex.: caixas para 12 brigadeiros, forminhas nº 4'
    },
    revenda: {
      opening: 'Olá, Megamix! Vim pelo site. Gostaria de consultar estes itens para revenda:',
      placeholder: 'Ex.: produto, marca e quantidade'
    },
    produto: {
      opening: 'Olá, Megamix! Vim pelo site e queria saber se vocês têm:',
      placeholder: 'Ex.: o nome do produto que você procura'
    }
  };
  var DEFAULT_OPENING = 'Olá, Megamix! Vim pelo site e queria saber se vocês têm:';
  var DEFAULT_PLACEHOLDER = 'Ex.: balas sortidas, copos para aniversário, caixas para brigadeiro';

  function formatTime(minutes) {
    var h = Math.floor(minutes / 60);
    var m = minutes % 60;
    return h + 'h' + (m ? String(m).padStart(2, '0') : '');
  }

  function localNow(date) {
    var parts = new Intl.DateTimeFormat('en-US', {
      timeZone: TIME_ZONE, weekday: 'short', hour: 'numeric', minute: 'numeric', hourCycle: 'h23'
    }).formatToParts(date);
    var get = function (type) { return parts.find(function (p) { return p.type === type; }).value; };
    var day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].indexOf(get('weekday'));
    return { day: day, minutes: Number(get('hour')) * 60 + Number(get('minute')) };
  }

  // Retorna se a loja está aberta e o texto para o selo de status.
  function getStatus(day, minutes) {
    var today = HOURS[day];
    for (var i = 0; i < today.length; i++) {
      if (minutes >= today[i][0] && minutes < today[i][1]) {
        return { open: true, text: 'Aberto agora · fecha às ' + formatTime(today[i][1]) };
      }
    }
    for (var j = 0; j < today.length; j++) {
      if (minutes < today[j][0]) {
        return { open: false, text: 'Fechado agora · abre às ' + formatTime(today[j][0]) };
      }
    }
    for (var k = 1; k <= 7; k++) {
      var next = (day + k) % 7;
      if (HOURS[next].length) {
        var when = k === 1 ? 'amanhã' : DAY_NAMES[next];
        return { open: false, text: 'Fechado agora · abre ' + when + ' às ' + formatTime(HOURS[next][0][0]) };
      }
    }
    return { open: false, text: 'Fechado agora' };
  }

  function buildMessage(intent, items, details) {
    var extra = (details || '').trim();
    var config = INTENTS[intent];
    if (!items.length && !extra) {
      return 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.';
    }
    var lines = [config ? config.opening : DEFAULT_OPENING];
    items.forEach(function (item) { lines.push('• ' + item); });
    if (extra) lines.push(items.length ? '' : null, extra);
    return lines.filter(function (line) { return line !== null; }).join('\n');
  }

  function submitLabel(count) {
    if (!count) return 'Consultar no WhatsApp';
    return 'Consultar ' + count + (count === 1 ? ' tipo de item' : ' tipos de item') + ' no WhatsApp';
  }

  function placeholderFor(intent) {
    return INTENTS[intent] ? INTENTS[intent].placeholder : DEFAULT_PLACEHOLDER;
  }

  function whatsappUrl(message) {
    return 'https://wa.me/' + WHATSAPP + '?text=' + encodeURIComponent(message);
  }

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
      getStatus: getStatus, buildMessage: buildMessage, submitLabel: submitLabel,
      placeholderFor: placeholderFor, whatsappUrl: whatsappUrl, localNow: localNow
    };
    return;
  }

  // Selos de aberto/fechado e destaque do dia na tabela de horários.
  function renderStatus() {
    var now = localNow(new Date());
    var status = getStatus(now.day, now.minutes);
    document.querySelectorAll('[data-status]').forEach(function (badge) {
      badge.classList.toggle('is-open', status.open);
      badge.classList.toggle('is-closed', !status.open);
      badge.querySelector('[data-status-text]').textContent = status.text;
    });
    document.querySelectorAll('[data-hours] tr').forEach(function (row) {
      var days = row.dataset.days.split(' ').map(Number);
      row.classList.toggle('is-today', days.indexOf(now.day) !== -1);
    });
  }

  renderStatus();
  setInterval(renderStatus, 60000);

  // Lista de consulta: o botão do formulário e o da barra fixa enviam a mesma mensagem.
  var form = document.querySelector('[data-picker]');
  if (!form) return;

  var picked = form.querySelector('[data-picked]');
  var submit = form.querySelector('[data-submit-label]');
  var dockLink = document.querySelector('[data-whats-link]');
  var dockLabel = document.querySelector('[data-dock-label]');

  function state() {
    var intent = form.querySelector('input[name="intencao"]:checked');
    var items = Array.prototype.map.call(
      form.querySelectorAll('input[name="item"]:checked'),
      function (input) { return input.value; }
    );
    return { intent: intent ? intent.value : null, items: items, details: form.elements.detalhes.value };
  }

  function currentUrl() {
    var s = state();
    return whatsappUrl(buildMessage(s.intent, s.items, s.details));
  }

  function refresh() {
    var s = state();
    submit.textContent = submitLabel(s.items.length);
    dockLabel.textContent = s.items.length ? 'Enviar minha lista' : 'Consultar no WhatsApp';
    form.elements.detalhes.placeholder = placeholderFor(s.intent);
    if (s.items.length) {
      picked.innerHTML = '';
      picked.append('Na lista: ');
      var strong = document.createElement('strong');
      strong.textContent = s.items.join(', ');
      picked.append(strong);
    } else {
      picked.textContent = 'Nada marcado ainda. Você também pode só escrever.';
    }
    dockLink.href = currentUrl();
  }

  form.addEventListener('change', refresh);
  form.addEventListener('input', refresh);
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    window.open(currentUrl(), '_blank', 'noopener');
  });
  dockLink.addEventListener('click', function () { dockLink.href = currentUrl(); });

  refresh();
})();
