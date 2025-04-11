import CreateTaskForm from "@/components/TaskForm/CreateTaskForm";
import { ArrowLeft } from "lucide-react";
import { useNavigate } from "react-router-dom";

export default function CreateTaskPage() {
  const navigate = useNavigate();

  return (
    <div className="container py-4">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <button
          className="btn btn-outline-secondary mb-1 d-flex align-items-center gap-1"
          onClick={() => navigate("/")}
        >
          <ArrowLeft size={18} /> Voltar para a Home
        </button>
      </div>
      <CreateTaskForm />
    </div>
  );
}
