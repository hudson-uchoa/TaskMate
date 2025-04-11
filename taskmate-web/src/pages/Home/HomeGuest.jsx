import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import api from "@/lib/api";
import { Button } from "@/components/ui/Button";
import { CheckCircle, Circle, PlusCircle } from "lucide-react";

export default function Dashboard() {
  const navigate = useNavigate();
  const [tasks, setTasks] = useState([]);
  const [userName, setUserName] = useState("Usuário");

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await api.get("/tasks");
        const data = Array.isArray(response?.data) ? response.data : [];
        setTasks(data);

        // Futuro: pegar nome do usuário
        // const user = await fetchUser();
        // setUserName(user?.name || "Usuário");
      } catch (err) {
        console.error("Erro ao buscar tarefas:", err);
      }
    };

    fetchData();
  }, []);

  const total = tasks.length;
  const completed = tasks.filter((t) => t.completed).length;
  const pending = total - completed;

  return (
    <motion.div
      className="container py-4"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
    >
      <h4 className="mb-4">Olá, {userName.split(" ")[0]} 👋</h4>

      {/* Cards de contagem */}
      <div className="row mb-4">
        {[
          { label: "Total", value: total, color: "primary" },
          { label: "Concluídas", value: completed, color: "success" },
          { label: "Pendentes", value: pending, color: "warning" },
        ].map(({ label, value, color }, index) => (
          <div key={index} className="col-12 col-md-4 mb-3">
            <div className="card shadow-sm">
              <div className="card-body text-center">
                <h5 className={`card-title text-${color}`}>{value}</h5>
                <p className="card-text text-muted mb-0">{label}</p>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Botões de ação */}
      <div className="d-flex flex-wrap gap-2 mb-4">
        <Button size="sm" onClick={() => navigate("/tasks/new")}>
          <PlusCircle className="me-2" size={16} /> Nova Tarefa
        </Button>
        <Button size="sm" variant="outline" onClick={() => navigate("/tasks")}>
          Ver Todas
        </Button>
      </div>

      {/* Lista de últimas tarefas */}
      <h6 className="mb-3">Últimas tarefas</h6>
      <ul className="list-group shadow-sm">
        {tasks.length === 0 ? (
          <li className="list-group-item text-center text-muted">
            Nenhuma tarefa cadastrada ainda.
          </li>
        ) : (
          tasks.slice(0, 3).map((task) => (
            <li
              key={task.id}
              className="list-group-item d-flex justify-content-between align-items-center"
            >
              {task.title}
              {task.completed ? (
                <CheckCircle size={18} className="text-success" />
              ) : (
                <Circle size={18} className="text-muted" />
              )}
            </li>
          ))
        )}
      </ul>
    </motion.div>
  );
}
