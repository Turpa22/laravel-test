<?php // МОДАЛЬНАЯ ФОРМА ДЛЯ РЕДАКТИРОВАНИЯ ЗАДАЧИ ?>

<div class="modal fade" id="task_edit" aria-hidden="true" task_id="0">
  <div class="modal-dialog" style="top: 50vh; transform: translateY(-50%); margin: auto; width: 380px;">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="taskInfoLabel">Задача</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        <form class="edit-task-form" id="editTaskForm">
          <input id="task_id" type="hidden" name="task_id" value="0"/>
          <div class="form-group col-md-12">
            <label for="task_title">Наименование задачи</label>
            <input id="task_title" type="text" class="form-control" name="task_title" placeholder="Название задачи"/>
          </div>
          <div class="form-group col-md-12">
						<label for="task_description">Описание задачи</label>
            <textarea id="task_description" class="form-control" name="task_description" placeholder="Описание задачи" rows="3"></textarea>
          </div>
          <div class="form-group col-md-6">
						<label for="task_status">Статус задачи</label>
            <select id="task_status" class="form-control" name="task_status">
              <option value="1" selected>Новая</option>
              <option value="2">В работе</option>
              <option value="3">Исполнена</option>
              <option value="4">Отклонена</option>
              <option value="5">Отменена</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">			
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
        <button class="btn btn-primary" id="editTask" name="editTask" onclick="save_task()">
          Сохранить 
        </button>
      </div>
      
    </div>
  </div>
</div>

<?php // ФОРМА ОЖИДАНИЯ ПРОЦЕССА ?>

<div id="waiting"></div>