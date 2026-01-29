// Загрузка списка задач
function load_tasks(){
  $('#waiting').show();
  $('.tasks__container').html('<p class="tasks__msg">Загрузка списка задач...</p>');
  console.info('Загрузка задач');
  $.ajax({
    url: '/api/tasks-table',
    method: 'GET',
    success: function(data){
      $('.tasks__container').html(data);
      $('#waiting').hide();
      
      // Добавляем обработчики событий для кнопок редактирования и удаления
      $('.edit-task').click(function() {
        var taskId = $(this).data('id');
        edit_task(taskId);
      });
      
      $('.delete-task').click(function() {
        var taskId = $(this).data('id');
        delete_task(taskId);
      });
    },
    error: function(data){
      console.error(data);
      $('#waiting').hide();
      $('.tasks__container').html('<p class="tasks__msg">Ошибка загрузки списка задач</p>');
    }
  });
  return false;
}
// Новая задача
function new_task(){
  console.info('Новая задача');
  show_task_form(0);
  return false;
}
// Редактирование задачи
function edit_task(id){
  console.info('Редактирование задачи ' + id);
  show_task_form(id);
  return false;
}
// Удаление задачи
function delete_task(id){
  console.info('Удаление задачи ' + id);
  
  if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
    $.ajax({
      url: '/api/tasks/' + id,
      method: 'DELETE',
      success: function(data){
        console.info('Задача удалена');
        load_tasks();
      },
      error: function(xhr, status, error){
        console.error('Ошибка удаления задачи:', error);
        alert('Ошибка удаления задачи');
      }
    });
  }
  
  return false;
}
// Форма редактирования задачи
function show_task_form(id){
  // Загружаем данные задачи, если редактируем существующую
  if (id > 0) {
    $.ajax({
      url: '/api/tasks/' + id,
      method: 'GET',
      success: function(data){
        $('#task_id').val(data.id);
        $('#task_title').val(data.title);
        $('#task_description').val(data.description);
        $('#task_status').val(data.status);
        $('#task_edit').modal('show');
      },
      error: function(xhr, status, error){
        console.error('Ошибка загрузки задачи:', error);
        alert('Ошибка загрузки задачи');
      }
    });
  } else {
    // Очищаем форму для новой задачи
    $('#task_id').val(0);
    $('#task_title').val('');
    $('#task_description').val('');
    $('#task_status').val('1');
    $('#task_edit').modal('show');
  }
}
// Сохранение задачи
function save_task(){
  var taskId = $('#task_id').val();
  var taskData = {
    title: $('#task_title').val(),
    description: $('#task_description').val(),
    status: $('#task_status').val()
  };
  
  var method = 'POST';
  var url = '/api/tasks';
  
  if (taskId>0) {
    method = 'PUT';
    url = '/api/tasks/' + taskId;
  }
  
  $.ajax({
    url: url,
    method: method,
    contentType: 'application/json',
    data: JSON.stringify(taskData),
    success: function(data){
      console.info('Задача сохранена');
      $('#task_edit').modal('hide');
      load_tasks();
    },
    error: function(xhr, status, error){
      console.error('Ошибка сохранения задачи:', error);
      var errorMessage = 'Ошибка сохранения задачи';
      if (xhr.responseJSON && xhr.responseJSON.errors) {
        errorMessage += ': ' + JSON.stringify(xhr.responseJSON.errors);
      }
      alert(errorMessage);
    }
  });
}

function check_task_data(){
  var title = $('#task_title').val();
  if (!title || title.trim() === '') {
    alert('Пожалуйста, введите название задачи');
    return false;
  }
  return true;
}

// Загружаем задачи при загрузке страницы
$(document).ready(function() {
  load_tasks();
  
  // Обработчик для кнопки создания новой задачи
  $('#new_task_btn').click(new_task);
  
  // Обработчик для кнопки сохранения задачи
  $('#save_task_btn').click(function() {
    if (check_task_data()) {
      save_task();
    }
  });
});