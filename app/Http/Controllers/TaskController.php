<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        
        // Добавляем текстовое представление статуса к каждой задаче
        $tasks->each(function ($task) {
            $task->status_text = $task->getStatusTextAttribute();
        });
        
        return $tasks;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|integer|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json(['error' => 'Задача #'.$id.' не найдена!'], 404);
        }
        
        // Добавляем текстовое представление статуса
        $task->status_text = $task->getStatusTextAttribute();
        
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json(['error' => 'Задача #'.$id.' не найдена!'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|integer|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();
        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json(['error' => 'Задача #'.$id.' не найдена!'], 404);
        }
        
        $task->delete();
        return response()->json(null, 204);
    }
    
    /**
     * Display a listing of the resource as HTML table.
     */
    public function table()
    {
        $tasks = Task::all();
        
        $html = '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Название</th>';
        $html .= '<th>Описание</th>';
        $html .= '<th>Статус</th>';
        $html .= '<th>Дата<br/>создания</th>';
        $html .= '<th>Дата<br/>обновления</th>';
        $html .= '<th></th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($tasks as $task) {
            $created_at = date('d.m.Y H:i', strtotime($task->created_at));
            $updated_at = date('d.m.Y H:i', strtotime($task->updated_at));
            $html .= '<tr>';
            $html .= '<td>' . $task->id . '</td>';
            $html .= '<td>' . htmlspecialchars($task->title) . '</td>';
            $html .= '<td>' . htmlspecialchars($task->description ?? '') . '</td>';
            $html .= '<td class="task_status status_' . $task->status . '">' . $task->getStatusTextAttribute() . '</td>';
            $html .= '<td>' . $created_at . '</td>';
            $html .= '<td>' . $updated_at . '</td>';
            $html .= '<td>'.

'<a class="edit-task" href="#" title="Изменить" data-id="' . $task->id . '"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none">
<path d="M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21H7C4.79086 21 3 19.2091 3 17V7Z" stroke="#000000" stroke-width="1"/>
<path d="M16.2739 11.1377C16.6644 10.7472 16.6644 10.114 16.2739 9.7235L14.4823 7.9319C14.0918 7.54137 13.4586 7.54138 13.0681 7.9319L8.96106 12.0389L8.34768 15.7477C8.3365 15.8154 8.39516 15.874 8.4628 15.8627L12.1669 15.2448L16.2739 11.1377Z" stroke="#000000" stroke-width="1"/>
</svg></a>'.

'<a class="delete-task" href="#" title="Удалить" data-id="' . $task->id . '"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none">
<path d="M5.16565 10.1534C5.07629 8.99181 5.99473 8 7.15975 8H16.8402C18.0053 8 18.9237 8.9918 18.8344 10.1534L18.142 19.1534C18.0619 20.1954 17.193 21 16.1479 21H7.85206C6.80699 21 5.93811 20.1954 5.85795 19.1534L5.16565 10.1534Z" stroke="#000000" stroke-width="1"/>
<path d="M19.5 5H4.5" stroke="#000000" stroke-width="1" stroke-linecap="round"/>
<path d="M10 3C10 2.44772 10.4477 2 11 2H13C13.5523 2 14 2.44772 14 3V5H10V3Z" stroke="#000000" stroke-width="1"/>
<path d="M14 12V17" stroke="#000000" stroke-width="1" stroke-linecap="round"/>
<path d="M10 12V17" stroke="#000000" stroke-width="1" stroke-linecap="round"/>
</svg></a>'.

            '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
