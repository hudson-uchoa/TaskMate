import TaskList from "@/components/TaskList/TaskList";
import { useNavigate } from "react-router-dom";
import { ArrowLeft } from "lucide-react";

export default function TasksPage() {
  const navigate = useNavigate();

  return (
    <div className="container min-vh-100 py-4">
      <button
        className="btn btn-outline-secondary mb-3 d-flex align-items-center gap-2"
        onClick={() => navigate("/")}
      >
        <ArrowLeft size={18} /> Voltar para a Home
      </button>

      <h1 className="text-2xl fw-bold mb-4">Minhas tarefas</h1>
      <TaskList />
    </div>
  );
}
