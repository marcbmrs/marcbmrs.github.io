(function () {
  'use strict';

  var WHATSAPP = '5551995481422';
  var TIME_ZONE = 'America/Sao_Paulo';
  var STORAGE_KEY = 'megamix-lista';

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

  // Vitrines. Categorias vêm da placa da fachada; os itens são tipos de produto, sem marca nem preço.
  var SHELVES = [
    { id: 'doces', title: 'Doces e guloseimas' },
    { id: 'festa', title: 'Para sua festa' },
    { id: 'embalagens', title: 'Embalagens e descartáveis' },
    { id: 'escola', title: 'Material escolar' },
    { id: 'mercado', title: 'Alimentos em geral' }
  ];

  var PRODUCTS = [
    { id: 'balas', shelf: 'doces', name: 'Balas sortidas', tag: 'Pacote e a granel', icon: 'bala', tint: 't-pink', words: 'bala balinha goma jujuba' },
    { id: 'pirulitos', shelf: 'doces', name: 'Pirulitos', tag: 'Sabores variados', icon: 'pirulito', tint: 't-yellow', words: 'pirulito' },
    { id: 'chicletes', shelf: 'doces', name: 'Chicletes', tag: 'Caixinha e pacote', icon: 'chiclete', tint: 't-mint', words: 'chiclete goma de mascar' },
    { id: 'chocolates', shelf: 'doces', name: 'Chocolates', tag: 'Barras e tabletes', icon: 'chocolate', tint: 't-orange', words: 'chocolate barra' },
    { id: 'bombons', shelf: 'doces', name: 'Bombons', tag: 'Unidade e caixa', icon: 'bombom', tint: 't-lilac', words: 'bombom' },
    { id: 'salgadinhos', shelf: 'doces', name: 'Salgadinhos', tag: 'Para lanche e festa', icon: 'salgadinho', tint: 't-yellow', words: 'salgadinho chips snack' },
    { id: 'baloes', shelf: 'festa', name: 'Balões', tag: 'Para decorar', icon: 'balao', tint: 't-pink', words: 'balao bexiga' },
    { id: 'velas', shelf: 'festa', name: 'Velas de aniversário', tag: 'Para o bolo', icon: 'vela', tint: 't-mint', words: 'vela numero aniversario bolo' },
    { id: 'decoracao', shelf: 'festa', name: 'Decoração de festa', tag: 'Mesa e ambiente', icon: 'bandeirinha', tint: 't-lilac', words: 'decoracao enfeite bandeirinha festa' },
    { id: 'saquinhos', shelf: 'festa', name: 'Saquinhos surpresa', tag: 'Para lembrancinhas', icon: 'saquinho', tint: 't-pink', words: 'saquinho sacola lembrancinha surpresa' },
    { id: 'caixas', shelf: 'embalagens', name: 'Caixas para doces', tag: 'Embalagens', icon: 'caixa', tint: 't-orange', words: 'caixa embalagem brigadeiro forminha' },
    { id: 'copos', shelf: 'embalagens', name: 'Copos descartáveis', tag: 'Descartáveis', icon: 'copo', tint: 't-sky', words: 'copo descartavel' },
    { id: 'pratos', shelf: 'embalagens', name: 'Pratos e talheres', tag: 'Descartáveis', icon: 'prato', tint: 't-yellow', words: 'prato talher garfo colher faca descartavel' },
    { id: 'cadernos', shelf: 'escola', name: 'Cadernos', tag: 'Material escolar', icon: 'caderno', tint: 't-sky', words: 'caderno escola' },
    { id: 'lapis', shelf: 'escola', name: 'Lápis e canetas', tag: 'Material escolar', icon: 'lapis', tint: 't-yellow', words: 'lapis caneta borracha escola' },
    { id: 'mercearia', shelf: 'mercado', name: 'Itens de mercearia', tag: 'Pergunte pelo que precisa', icon: 'mercado', tint: 't-green', words: 'alimento mercado mercearia comida' }
  ];

  var TICKER = [
    'Atacarejo de doces e festas no Guajuviras',
    'Monte sua lista e consulte pelo WhatsApp',
    'Seg. a sex. 9h–19h · Sáb. 9h–12h e 14h–19h'
  ];

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

  function normalize(text) {
    return (text || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').trim();
  }

  // Ids dos produtos que combinam com a busca; todas as palavras precisam aparecer.
  function searchProducts(products, term) {
    var words = normalize(term).split(/\s+/).filter(Boolean);
    if (!words.length) return products.map(function (p) { return p.id; });
    return products.filter(function (p) {
      var haystack = normalize(p.name + ' ' + p.tag + ' ' + p.words);
      return words.every(function (w) { return haystack.indexOf(w) !== -1 || haystack.indexOf(w.replace(/s$/, '')) !== -1; });
    }).map(function (p) { return p.id; });
  }

  function buildListMessage(names, note) {
    var extra = (note || '').trim();
    if (!names.length && !extra) return 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.';
    var lines = ['Olá, Megamix! Vim pelo site e gostaria de consultar esta lista:'];
    names.forEach(function (name) { lines.push('• ' + name); });
    if (extra) {
      if (names.length) lines.push('');
      lines.push(extra);
    }
    return lines.join('\n');
  }

  function buildSearchMessage(term) {
    return 'Olá, Megamix! Vim pelo site. Vocês têm ' + term.trim() + '?';
  }

  function whatsappUrl(message) {
    return 'https://wa.me/' + WHATSAPP + '?text=' + encodeURIComponent(message);
  }

  // Lê a lista salva, ignorando ids que não existem mais.
  function parseSaved(raw, products) {
    try {
      var ids = JSON.parse(raw);
      if (!Array.isArray(ids)) return [];
      return ids.filter(function (id) { return products.some(function (p) { return p.id === id; }); });
    } catch (e) {
      return [];
    }
  }

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
      PRODUCTS: PRODUCTS, SHELVES: SHELVES, getStatus: getStatus, localNow: localNow,
      searchProducts: searchProducts, buildListMessage: buildListMessage,
      buildSearchMessage: buildSearchMessage, whatsappUrl: whatsappUrl, parseSaved: parseSaved
    };
    return;
  }

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var byId = {};
  PRODUCTS.forEach(function (p) { byId[p.id] = p; });

  /* ---------- Vitrines ---------- */
  var shelvesRoot = document.querySelector('[data-shelves]');
  SHELVES.forEach(function (shelf) {
    var section = document.createElement('section');
    section.className = 'section';
    section.id = shelf.id;
    section.setAttribute('aria-labelledby', shelf.id + '-titulo');
    section.innerHTML =
      '<div class="container section-head"><h2 class="section-title" id="' + shelf.id + '-titulo">' + shelf.title + '</h2></div>' +
      '<div class="scroller shelf"></div>';
    var row = section.querySelector('.shelf');
    PRODUCTS.filter(function (p) { return p.shelf === shelf.id; }).forEach(function (p) {
      var card = document.createElement('article');
      card.className = 'card';
      card.dataset.product = p.id;
      card.innerHTML =
        '<div class="card-img ' + p.tint + '"><svg aria-hidden="true"><use href="#i-' + p.icon + '"/></svg></div>' +
        '<div class="card-body"><h3 class="card-name">' + p.name + '</h3>' +
        '<p class="card-tag">' + p.tag + '</p>' +
        '<button class="add" type="button" aria-pressed="false" data-add="' + p.id + '">+ Lista</button></div>';
      row.appendChild(card);
    });
    shelvesRoot.appendChild(section);
  });

  /* ---------- Lista ---------- */
  var list = [];
  try { list = parseSaved(localStorage.getItem(STORAGE_KEY), PRODUCTS); } catch (e) { list = []; }

  var count = document.querySelector('[data-count]');
  var basket = document.querySelector('[data-basket]');
  var listItems = document.querySelector('[data-list-items]');
  var listEmpty = document.querySelector('[data-list-empty]');
  var note = document.querySelector('[data-note]');
  var sendLink = document.querySelector('[data-send]');
  var toast = document.querySelector('[data-toast]');
  var toastText = document.querySelector('[data-toast-text]');
  var toastTimer;

  function currentMessage() {
    return buildListMessage(list.map(function (id) { return byId[id].name; }), note.value);
  }

  function save() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(list)); } catch (e) { /* sem armazenamento: a lista vale só nesta visita */ }
  }

  function render() {
    count.textContent = list.length;
    count.hidden = !list.length;
    basket.setAttribute('aria-label', 'Minha lista, ' + list.length + (list.length === 1 ? ' item' : ' itens'));
    document.querySelectorAll('[data-add]').forEach(function (button) {
      var inList = list.indexOf(button.dataset.add) !== -1;
      button.setAttribute('aria-pressed', inList);
      button.textContent = inList ? '✓ Na lista' : '+ Lista';
    });
    listItems.innerHTML = '';
    list.forEach(function (id) {
      var p = byId[id];
      var li = document.createElement('li');
      li.innerHTML = '<svg aria-hidden="true"><use href="#i-' + p.icon + '"/></svg><span></span>' +
        '<button type="button" data-remove="' + id + '" aria-label="Tirar ' + p.name + ' da lista">×</button>';
      li.querySelector('span').textContent = p.name;
      listItems.appendChild(li);
    });
    listEmpty.hidden = list.length > 0;
    var url = whatsappUrl(currentMessage());
    sendLink.href = url;
    document.querySelectorAll('[data-whats]').forEach(function (link) { link.href = url; });
  }

  function showToast(text) {
    toastText.textContent = text;
    toast.hidden = false;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.hidden = true; }, 3500);
  }

  document.addEventListener('click', function (event) {
    var add = event.target.closest('[data-add]');
    if (add) {
      var id = add.dataset.add;
      var at = list.indexOf(id);
      if (at === -1) {
        list.push(id);
        showToast(byId[id].name + ' na lista');
        if (!reduceMotion) {
          basket.classList.remove('is-bumped');
          void basket.offsetWidth;
          basket.classList.add('is-bumped');
        }
      } else {
        list.splice(at, 1);
      }
      save();
      render();
      return;
    }
    var remove = event.target.closest('[data-remove]');
    if (remove) {
      list.splice(list.indexOf(remove.dataset.remove), 1);
      save();
      render();
    }
  });

  note.addEventListener('input', render);
  document.querySelector('[data-clear]').addEventListener('click', function () {
    list = [];
    note.value = '';
    save();
    render();
  });

  /* ---------- Gavetas ---------- */
  document.addEventListener('click', function (event) {
    var opener = event.target.closest('[data-open]');
    if (opener) {
      var dialog = document.querySelector('[data-dialog="' + opener.dataset.open + '"]');
      toast.hidden = true;
      if (!dialog.open) dialog.showModal();
      return;
    }
    var closer = event.target.closest('[data-close]');
    if (closer) closer.closest('dialog').close();
  });
  document.querySelectorAll('dialog').forEach(function (dialog) {
    dialog.addEventListener('click', function (event) {
      if (event.target === dialog) dialog.close();
    });
  });

  /* ---------- Busca ---------- */
  var searchForm = document.querySelector('[data-search]');
  var searchInput = searchForm.elements.q;
  var searchEmpty = document.querySelector('[data-search-empty]');

  function applySearch() {
    var term = searchInput.value;
    var found = searchProducts(PRODUCTS, term);
    document.querySelectorAll('[data-product]').forEach(function (card) {
      card.hidden = found.indexOf(card.dataset.product) === -1;
    });
    SHELVES.forEach(function (shelf) {
      document.getElementById(shelf.id).hidden = !document.querySelector('#' + shelf.id + ' [data-product]:not([hidden])');
    });
    if (term.trim() && !found.length) {
      searchEmpty.innerHTML = 'Não encontramos “<strong></strong>” na vitrine. <a target="_blank" rel="noopener">Perguntar no WhatsApp se tem</a>';
      searchEmpty.querySelector('strong').textContent = term.trim();
      searchEmpty.querySelector('a').href = whatsappUrl(buildSearchMessage(term));
      searchEmpty.hidden = false;
    } else {
      searchEmpty.hidden = true;
    }
    return found;
  }

  searchInput.addEventListener('input', applySearch);
  searchForm.addEventListener('submit', function (event) {
    event.preventDefault();
    var found = applySearch();
    var target = found.length ? document.querySelector('[data-product="' + found[0] + '"]').closest('section') : searchEmpty;
    target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
    searchInput.blur();
  });

  /* ---------- Banner ---------- */
  var track = document.querySelector('[data-banner]');
  var dots = Array.prototype.slice.call(document.querySelectorAll('[data-dots] button'));
  var autoplay;

  function currentSlide() { return Math.round(track.scrollLeft / track.clientWidth); }
  function goTo(index) { track.scrollTo({ left: index * track.clientWidth, behavior: reduceMotion ? 'auto' : 'smooth' }); }
  function stopAutoplay() { clearInterval(autoplay); }

  track.addEventListener('scroll', function () {
    var index = currentSlide();
    dots.forEach(function (dot, i) { dot.setAttribute('aria-current', i === index); });
  }, { passive: true });
  dots.forEach(function (dot, i) {
    dot.addEventListener('click', function () { stopAutoplay(); goTo(i); });
  });
  ['pointerdown', 'focusin', 'wheel'].forEach(function (type) { track.addEventListener(type, stopAutoplay, { passive: true }); });
  if (!reduceMotion) {
    autoplay = setInterval(function () { goTo((currentSlide() + 1) % dots.length); }, 6000);
  }

  /* ---------- Avisos do topo ---------- */
  var ticker = document.querySelector('[data-ticker]');
  if (!reduceMotion) {
    var tick = 0;
    setInterval(function () {
      ticker.classList.add('is-fading');
      setTimeout(function () {
        tick = (tick + 1) % TICKER.length;
        ticker.textContent = TICKER[tick];
        ticker.classList.remove('is-fading');
      }, 300);
    }, 4000);
  }

  /* ---------- Horário ---------- */
  function renderStatus() {
    var now = localNow(new Date());
    var status = getStatus(now.day, now.minutes);
    document.querySelectorAll('[data-status-text]').forEach(function (el) { el.textContent = status.text.split(' · ')[0]; });
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
  render();
})();
