import { useParams, useNavigate } from "react-router-dom";
import EditTaskForm from "@/components/TaskForm/EditTaskForm";
import { ArrowLeft } from "lucide-react";

export default function EditTaskPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  if (!id) return <p>ID inválido.</p>;

  return (
    <div className="container py-4 min-vh-100">
      <button
        className="btn btn-outline-secondary mb-3 d-flex align-items-center gap-2"
        onClick={() => navigate("/tasks")}
      >
        <ArrowLeft size={18} /> Voltar para a Home
      </button>
      <EditTaskForm taskId={id} />
    </div>
  );
}
