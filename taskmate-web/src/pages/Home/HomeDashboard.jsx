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
  const completed = tasks.filter((t) => t.status === "completed").length;
  const pending = tasks.filter((t) => t.status === "pending").length;

  return (
    <motion.div
      className="w-100 py-3"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
    >
      <div className="container-fluid px-3">
        <div className="bg-white rounded shadow-sm p-3 mx-auto">
          <h4 className="mb-3">Olá, {userName.split(" ")[0]} 👋</h4>

          {/* Cards - Ajustado para mobile */}
          <div className="row g-3 justify-content-center mb-3">
            <div className="col-4">
              <div className="card shadow-sm h-100">
                <div className="card-body text-center p-2">
                  <h5 className="text-primary mb-1">{total}</h5>
                  <p className="text-muted mb-0 small">Total</p>
                </div>
              </div>
            </div>
            <div className="col-4">
              <div className="card shadow-sm h-100">
                <div className="card-body text-center p-2">
                  <h5 className="text-success mb-1">{completed}</h5>
                  <p className="text-muted mb-0 small">Concluídas</p>
                </div>
              </div>
            </div>
            <div className="col-4">
              <div className="card shadow-sm h-100">
                <div className="card-body text-center p-2">
                  <h5 className="text-warning mb-1">{pending}</h5>
                  <p className="text-muted mb-0 small">Pendentes</p>
                </div>
              </div>
            </div>
          </div>

          {/* Botões - Ajustado para mobile */}
          <div className="d-flex flex-column gap-2 mb-3">
            <Button
              className="btn btn-success d-flex align-items-center justify-content-center gap-2 py-2"
              onClick={() => navigate("/tasks/create")}
            >
              <PlusCircle size={18} /> 
              <span>Nova Tarefa</span>
            </Button>
            <Button
              className="btn btn-outline-dark d-flex align-items-center justify-content-center gap-2 py-2"
              onClick={() => navigate("/tasks")}
            >
              <span>Ver Todas</span>
            </Button>
          </div>

          {/* Últimas tarefas - Ajustado para mobile */}
          <h6 className="mb-2">Últimas tarefas</h6>
          <ul className="list-group shadow-sm mb-3">
            {tasks.length === 0 ? (
              <li className="list-group-item text-center text-muted small py-2">
                Nenhuma tarefa cadastrada ainda.
              </li>
            ) : (
              tasks.slice(0, 3).map((task) => (
                <li
                  key={task.id}
                  className="list-group-item d-flex justify-content-between align-items-center py-2 px-3"
                  onClick={() => navigate(`/tasks/edit/${task.id}`)}
                  style={{ cursor: "pointer" }}
                >
                  <span className="text-truncate" style={{ maxWidth: "80%" }}>
                    {task.title}
                  </span>
                  {task.completed ? (
                    <CheckCircle size={18} className="text-success" />
                  ) : (
                    <Circle size={18} className="text-muted" />
                  )}
                </li>
              ))
            )}
          </ul>
        </div>
      </div>
    </motion.div>
  );
}