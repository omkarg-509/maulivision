<?php require_once '../app/views/layouts/sidebar.php'; ?>
<style>
  #todo-list .todo-title{transition:color .25s, text-decoration-color .25s;}
  #todo-list li.new-item{animation: flashBg 1.2s ease-in-out 1;}
  @keyframes flashBg {0%{background:#e3f2ff;}60%{background:#ffffff;}100%{background:#ffffff;}}
  #todo-list li{transition:background .3s;}
  #todo-list li:hover{background:#f8f9fa;}
</style>
<div class="main-content mt-5 pt-4">
  <section class="section">
    <div class="section-header">
      <h1>Your To‑Do List</h1>
    </div>
    <div class="card">
      <div class="card-body">
        <form id="todo-form" class="row g-2 mb-3" autocomplete="off">
          <div class="col-sm-9 col-md-10">
            <input type="text" id="todo-title" class="form-control" placeholder="Add new task..." required>
          </div>
          <div class="col-sm-3 col-md-2 d-grid">
            <button class="btn btn-primary w-100" type="submit">Add</button>
          </div>
        </form>
        <div id="todo-empty" class="text-muted small <?= empty($todos) ? '' : 'd-none' ?>">No tasks yet. Add your first one.</div>
        <ul id="todo-list" class="list-group">
          <?php foreach ($todos as $t): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= (int)$t['id'] ?>">
              <div>
                <input type="checkbox" class="form-check-input me-2 todo-toggle" <?= $t['is_done'] ? 'checked' : '' ?>>
                <span class="todo-title <?= $t['is_done'] ? 'text-decoration-line-through text-muted' : '' ?>"><?= htmlspecialchars($t['title']) ?></span>
              </div>
              <button class="btn btn-sm btn-outline-danger todo-delete"><i class="fas fa-trash"></i></button>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>
</div>

<script>
(function(){
  const BASE = '<?= BASE_URL ?>';
  const form = document.getElementById('todo-form');
  const input = document.getElementById('todo-title');
  const list = document.getElementById('todo-list');
  const empty = document.getElementById('todo-empty');

  function toggleEmpty(){
    if(list.children.length === 0){ empty.classList.remove('d-none'); } else { empty.classList.add('d-none'); }
  }

  form.addEventListener('submit', function(e){
    e.preventDefault();
    const title = input.value.trim();
    if(!title) return;
    fetch(BASE+'own/add', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'title='+encodeURIComponent(title)})
      .then(r=>r.json())
      .then(data=>{
        if(data.ok){
          // Prepend new item (optimistic without id fetch)
          const li = document.createElement('li');
          li.className='list-group-item d-flex justify-content-between align-items-center new-item';
          li.innerHTML='<div><input type="checkbox" class="form-check-input me-2 todo-toggle"> <span class="todo-title">'+title.replace(/</g,'&lt;')+'</span></div><button class="btn btn-sm btn-outline-danger todo-delete"><i class="fas fa-trash"></i></button>';
          list.prepend(li);
          setTimeout(()=>li.classList.remove('new-item'),1300);
          input.value='';
          toggleEmpty();
        }
      });
  });

  list.addEventListener('click', function(e){
    const li = e.target.closest('li');
    if(!li) return;
    const id = li.getAttribute('data-id');

    if(e.target.closest('.todo-delete')){
      if(!id){ li.remove(); toggleEmpty(); return; }
      fetch(BASE+'own/delete/'+id)
        .then(r=>r.json()).then(data=>{ if(data.ok){ li.remove(); toggleEmpty(); }});
    }
    else if(e.target.classList.contains('todo-toggle')){
      if(!id){ e.target.checked = !e.target.checked; return; }
      fetch(BASE+'own/toggle/'+id)
        .then(r=>r.json()).then(data=>{
          if(data.ok){
            const span = li.querySelector('.todo-title');
            if(e.target.checked){ span.classList.add('text-decoration-line-through','text-muted'); }
            else { span.classList.remove('text-decoration-line-through','text-muted'); }
          } else {
            e.target.checked = !e.target.checked;
          }
        });
    }
  });

  toggleEmpty();
})();
</script>
