import React, { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { Pencil, Trash2, AlarmClock, ClipboardList } from "lucide-react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import withReactContent from "sweetalert2-react-content";
import { getTasks, deleteTask } from "@/services/task";

const MySwal = withReactContent(Swal);

const TaskList = () => {
  const [tasks, setTasks] = useState([]);
  const navigate = useNavigate();

  const fetchTasks = async () => {
    try {
      const data = await getTasks();
      setTasks(data.data.tasks);
    } catch (error) {
      console.error("Erro ao buscar tarefas:", error);
    }
  };

  useEffect(() => {
    fetchTasks();
  }, []);

  const handleDelete = async (id) => {
    const result = await MySwal.fire({
      title: "Tem certeza?",
      text: "Essa ação não pode ser desfeita.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sim, excluir",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d"
    });

    if (result.isConfirmed) {
      try {
        await deleteTask(id);
        await fetchTasks(); // atualiza a lista
        MySwal.fire("Excluído!", "A tarefa foi excluída.", "success");
      } catch (error) {
        console.error("Erro ao excluir tarefa:", error);
        MySwal.fire("Erro", "Não foi possível excluir a tarefa.", "error");
      }
    }
  };

  const getStatusBadge = (status) => {
    switch (status) {
      case "pending":
        return <span className="badge bg-warning text-dark">Pendente</span>;
      case "in_progress":
        return <span className="badge bg-info text-dark">Em progresso</span>;
      case "done":
        return <span className="badge bg-success">Concluída</span>;
      default:
        return <span className="badge bg-secondary">Desconhecido</span>;
    }
  };

  const getPriorityBadge = (priority) => {
    switch (priority) {
      case "low":
        return <span className="badge bg-secondary">Baixa</span>;
      case "normal":
        return <span className="badge bg-primary">Média</span>;
      case "high":
        return <span className="badge bg-danger">Alta</span>;
      default:
        return null;
    }
  };

  return (
    <div className="row g-4">
      {tasks.length === 0 ? (
        <div className="col-12 d-flex flex-column align-items-center justify-content-center text-center py-5">
          <ClipboardList size={48} className="text-muted mb-3" />
          <h4 className="text-muted">Nenhuma tarefa encontrada</h4>
          <p className="text-muted">Adicione uma nova tarefa para começar!</p>
        </div>
      ) : (
        tasks.map((task) => (
          <motion.div
            key={task.id}
            className="col-sm-12 col-md-6 col-lg-4"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3 }}
          >
            <div className="card shadow-sm rounded-4 h-100 border-0">
              <div className="card-body d-flex flex-column justify-content-between">
                <div className="d-flex justify-content-between align-items-start mb-2" style={{ minHeight: "3rem" }}>
                  <h5 className="card-title fw-bold">{task.title}</h5>
                  <div className="btn-group">
                    <button
                      className="btn btn-sm btn-outline-primary"
                      title="Editar"
                      onClick={() => navigate(`/tasks/edit/${task.id}`)}
                    >
                      <Pencil size={16} />
                    </button>
                    <button
                      className="btn btn-sm btn-outline-danger"
                      title="Excluir"
                      onClick={() => handleDelete(task.id)}
                    >
                      <Trash2 size={16} />
                    </button>
                  </div>
                </div>

                <p className="card-text text-muted small">{task.description}</p>

                <div className="d-flex flex-wrap gap-2 mt-3">
                  {getStatusBadge(task.status)}
                  {getPriorityBadge(task.priority)}
                  {task.category && (
                    <span className="badge bg-light text-dark border border-secondary">
                      {task.category}
                    </span>
                  )}
                  {task.dueDate && (
                    <span className="badge bg-dark-subtle text-dark d-flex align-items-center gap-1">
                      <AlarmClock size={14} /> {task.dueDate}
                    </span>
                  )}
                </div>
              </div>
            </div>
          </motion.div>
        ))
      )}
    </div>
  );
};

export default TaskList;
