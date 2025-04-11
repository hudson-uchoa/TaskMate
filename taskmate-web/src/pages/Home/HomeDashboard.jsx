import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import api from "@/lib/api";
import { fetchUser } from "@/services/auth.js";
import { Button } from "@/components/ui/Button";
import { CheckCircle, Circle, PlusCircle } from "lucide-react";

export default function Dashboard() {
  const navigate = useNavigate();
  const [tasks, setTasks] = useState([]);
  const [userName, setUserName] = useState("");

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [tasksRes, user] = await Promise.all([
          api.get("/tasks"),
          fetchUser(),
        ]);
        setTasks(tasksRes.data.tasks);
        setUserName(user.user.name);
      } catch (err) {
        console.error("Erro ao buscar dados:", err);
      }
    };

    fetchData();
  }, []);

  const total = tasks.length;
  const completed = tasks.filter((t) => t.status == 'completed').length;
  const pending = tasks.filter((t) => t.status == 'pending').length;

  return (
    <motion.div
      className="container py-4"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
    >
      <h4 className="mb-4">Olá, {userName.split(" ")[0]} 👋</h4>

      <div className="row mb-4">
        <div className="col-12 col-md-4 mb-3">
          <div className="card shadow-sm">
            <div className="card-body text-center">
              <h5 className="card-title text-primary">{total}</h5>
              <p className="card-text text-muted mb-0">Total</p>
            </div>
          </div>
        </div>
        <div className="col-12 col-md-4 mb-3">
          <div className="card shadow-sm">
            <div className="card-body text-center">
              <h5 className="card-title text-success">{completed}</h5>
              <p className="card-text text-muted mb-0">Concluídas</p>
            </div>
          </div>
        </div>
        <div className="col-12 col-md-4 mb-3">
          <div className="card shadow-sm">
            <div className="card-body text-center">
              <h5 className="card-title text-warning">{pending}</h5>
              <p className="card-text text-muted mb-0">Pendentes</p>
            </div>
          </div>
        </div>
      </div>

      <div className="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <Button size="sm" className="btn btn-success d-flex align-items-center justify-content-center gap-2" variant="outline-primary" onClick={() => navigate("/tasks/create")}>
          <PlusCircle className="me-2" size={16} /> Nova Tarefa
        </Button>
        <Button size="sm" className="btn btn-dark d-flex align-items-center justify-content-center gap-2" variant="outline-secondary" onClick={() => navigate("/tasks")}>
          Ver Todas
        </Button>
      </div>

      <h6 className="mb-3">Últimas tarefas</h6>
      <ul className="list-group shadow-sm">
        {tasks.length === 0 && (
          <li className="list-group-item text-center text-muted">
            Nenhuma tarefa cadastrada ainda.
          </li>
        )}
        {tasks.slice(0, 3).map((task) => (
          <li
            key={task.id}
            className="list-group-item d-flex justify-content-between align-items-center"
            onClick={() => navigate(`/tasks/edit/${task.id}`)}
            style={{ cursor: "pointer" }}
          >
            {task.title}
            {task.completed ? (
              <CheckCircle size={18} className="text-success" />
            ) : (
              <Circle size={18} className="text-muted" />
            )}
          </li>
        ))}
      </ul>
    </motion.div>
  );
}
