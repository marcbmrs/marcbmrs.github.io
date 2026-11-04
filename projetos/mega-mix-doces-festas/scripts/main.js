(function () {
  'use strict';
  var WHATSAPP = '5551995481422';
  var STORAGE_KEY = 'megamix-lista';
  var TIME_ZONE = 'America/Sao_Paulo';
  var HOURS = [[], [[540,1140]], [[540,1140]], [[540,1140]], [[540,1140]], [[540,1140]], [[540,720],[840,1140]]];
  var DAY_NAMES = ['domingo','segunda','terça','quarta','quinta','sexta','sábado'];
  // Categorias visíveis na fachada ou em fotos públicas. Estoque e formatos não foram confirmados.
  var CATEGORIES = [
    { id:'balas', name:'Balas e pirulitos', words:'bala balas pirulito pirulitos doce doces', color:'#e7aa80' },
    { id:'chicletes', name:'Chicletes', words:'chiclete goma de mascar', color:'#b9d7a8' },
    { id:'salgadinhos', name:'Salgadinhos', words:'salgadinho chips lanche', color:'#efd393' },
    { id:'chocolates', name:'Chocolates', words:'chocolate bombom bombons', color:'#cba891' },
    { id:'festas', name:'Artigos para festas', words:'festa festas balao baloes vela aniversario decoração decoracao', color:'#d8b9b8' },
    { id:'embalagens', name:'Embalagens', words:'embalagem embalagens caixa caixas', color:'#b2c9bb' },
    { id:'descartaveis', name:'Descartáveis', words:'descartavel descartaveis copo copos prato pratos', color:'#b4c8d3' },
    { id:'escolar', name:'Material escolar', words:'escola escolar caderno cadernos lapis caneta', color:'#c7bdd9' },
    { id:'alimentos', name:'Alimentos em geral', words:'alimento alimentos mercearia mercado', color:'#d5c39c' }
  ];
  function formatTime(minutes){ var h=Math.floor(minutes/60),m=minutes%60; return h+'h'+(m?String(m).padStart(2,'0'):''); }
  function localNow(date){
    var parts=new Intl.DateTimeFormat('en-US',{timeZone:TIME_ZONE,weekday:'short',hour:'numeric',minute:'numeric',hourCycle:'h23'}).formatToParts(date);
    var get=function(type){return parts.find(function(p){return p.type===type;}).value;};
    return {day:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].indexOf(get('weekday')),minutes:Number(get('hour'))*60+Number(get('minute'))};
  }
  function getStatus(day,minutes){
    var today=HOURS[day];
    for(var i=0;i<today.length;i++) if(minutes>=today[i][0]&&minutes<today[i][1]) return {open:true,text:'Aberto agora · fecha às '+formatTime(today[i][1])};
    for(var j=0;j<today.length;j++) if(minutes<today[j][0]) return {open:false,text:'Fechado agora · abre às '+formatTime(today[j][0])};
    for(var k=1;k<=7;k++){var next=(day+k)%7;if(HOURS[next].length)return {open:false,text:'Fechado agora · abre '+(k===1?'amanhã':DAY_NAMES[next])+' às '+formatTime(HOURS[next][0][0])};}
    return {open:false,text:'Fechado agora'};
  }
  function normalize(value){return String(value||'').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').trim();}
  function searchCategories(categories,term){
    var words=normalize(term).split(/\s+/).filter(Boolean);
    if(!words.length)return categories.map(function(c){return c.id;});
    return categories.filter(function(c){var haystack=normalize(c.name+' '+c.words);return words.every(function(w){return haystack.includes(w)||haystack.includes(w.replace(/s$/,''));});}).map(function(c){return c.id;});
  }
  function buildListMessage(names,note){
    var extra=String(note||'').trim();
    if(!names.length&&!extra)return 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.';
    var lines=['Olá, Megamix! Vim pelo site e gostaria de consultar estes itens:'];
    names.forEach(function(name){lines.push('• '+name);});
    if(extra){if(names.length)lines.push('');lines.push(extra);}
    return lines.join('\n');
  }
  function buildSearchMessage(term){return 'Olá, Megamix! Vim pelo site. Vocês têm '+String(term).trim()+'?';}
  function whatsappUrl(message){return 'https://wa.me/'+WHATSAPP+'?text='+encodeURIComponent(message);}
  function parseSaved(raw,categories){
    try{var ids=JSON.parse(raw);if(!Array.isArray(ids))return [];return [...new Set(ids.filter(function(id){return categories.some(function(c){return c.id===id;});}))];}catch(error){return [];}
  }
  if(typeof module!=='undefined'&&module.exports){
    module.exports={CATEGORIES:CATEGORIES,getStatus:getStatus,localNow:localNow,searchCategories:searchCategories,buildListMessage:buildListMessage,buildSearchMessage:buildSearchMessage,whatsappUrl:whatsappUrl,parseSaved:parseSaved};
    return;
  }
  var byId={}; CATEGORIES.forEach(function(c){byId[c.id]=c;});
  var grid=document.querySelector('[data-categories]');
  CATEGORIES.forEach(function(c,index){
    var card=document.createElement('article');card.className='category-card';card.dataset.category=c.id;card.style.setProperty('--card-color',c.color);
    var num=document.createElement('span');num.className='category-number';num.textContent=String(index+1).padStart(2,'0');
    var title=document.createElement('h3');title.textContent=c.name;
    var add=document.createElement('button');add.type='button';add.dataset.add=c.id;add.setAttribute('aria-pressed','false');add.textContent='Adicionar à lista +';
    card.append(num,title,add);grid.appendChild(card);
  });
  var list=[];try{list=parseSaved(localStorage.getItem(STORAGE_KEY),CATEGORIES);}catch(error){list=[];}
  var dialog=document.querySelector('[data-list-dialog]'),count=document.querySelector('[data-count]'),listItems=document.querySelector('[data-list-items]'),empty=document.querySelector('[data-list-empty]'),note=document.querySelector('[data-note]'),send=document.querySelector('[data-send]');
  function save(){try{localStorage.setItem(STORAGE_KEY,JSON.stringify(list));}catch(error){/* Lista apenas nesta visita. */}}
  function render(){
    count.textContent=list.length;count.hidden=!list.length;
    document.querySelector('.list-trigger').setAttribute('aria-label','Abrir minha lista de consulta, '+list.length+(list.length===1?' item':' itens'));
    document.querySelectorAll('[data-add]').forEach(function(button){var selected=list.includes(button.dataset.add);button.setAttribute('aria-pressed',String(selected));button.textContent=selected?'Na lista ✓':'Adicionar à lista +';});
    listItems.replaceChildren();
    list.forEach(function(id){var item=document.createElement('li'),label=document.createElement('span'),remove=document.createElement('button');label.textContent=byId[id].name;remove.type='button';remove.dataset.remove=id;remove.textContent='Remover';remove.setAttribute('aria-label','Remover '+byId[id].name+' da lista');item.append(label,remove);listItems.appendChild(item);});
    empty.hidden=list.length>0;send.href=whatsappUrl(buildListMessage(list.map(function(id){return byId[id].name;}),note.value));
  }
  document.addEventListener('click',function(event){
    var add=event.target.closest('[data-add]'),remove=event.target.closest('[data-remove]');
    if(add){var id=add.dataset.add,at=list.indexOf(id);if(at<0)list.push(id);else list.splice(at,1);save();render();}
    if(remove){list=list.filter(function(id){return id!==remove.dataset.remove;});save();render();}
  });
  document.querySelectorAll('[data-open-list]').forEach(function(button){button.addEventListener('click',function(){dialog.showModal();});});
  document.querySelector('[data-close-list]').addEventListener('click',function(){dialog.close();});
  dialog.addEventListener('click',function(event){if(event.target===dialog)dialog.close();});
  document.querySelector('[data-clear]').addEventListener('click',function(){list=[];note.value='';save();render();});
  note.addEventListener('input',render);
  var form=document.querySelector('[data-search]'),input=form.elements.q,feedback=document.querySelector('[data-search-feedback]');
  function applySearch(){
    var term=input.value,found=searchCategories(CATEGORIES,term);
    document.querySelectorAll('[data-category]').forEach(function(card){card.hidden=!found.includes(card.dataset.category);});
    feedback.replaceChildren();
    if(term.trim()&&!found.length){
      feedback.append('Não encontramos “'+term.trim()+'” nas categorias. ');
      var link=document.createElement('a');link.href=whatsappUrl(buildSearchMessage(term));link.target='_blank';link.rel='noopener';link.textContent='Perguntar à loja';feedback.appendChild(link);feedback.hidden=false;
    }else feedback.hidden=true;
    return found;
  }
  input.addEventListener('input',applySearch);
  form.addEventListener('submit',function(event){event.preventDefault();var found=applySearch(),target=found.length?document.querySelector('[data-category="'+found[0]+'"]'):feedback;target.scrollIntoView({behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth',block:'center'});});
  function renderStatus(){var now=localNow(new Date()),status=getStatus(now.day,now.minutes),statusEl=document.querySelector('[data-status]');statusEl.textContent=status.text;statusEl.classList.toggle('is-open',status.open);document.querySelectorAll('[data-status-short],[data-status-top]').forEach(function(el){el.textContent=status.text;});var wrap=document.querySelector('[data-status-wrap]');if(wrap)wrap.classList.toggle('is-open',status.open);document.querySelectorAll('[data-days]').forEach(function(row){row.classList.toggle('is-today',row.dataset.days.split(' ').includes(String(now.day)));});}
  renderStatus();setInterval(renderStatus,60000);render();
})();
