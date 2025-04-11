import { useState } from "react";
import { motion } from "framer-motion";
import { createTask } from "@/services/task";
import { useNavigate } from "react-router-dom";
import { formatDate } from "@/utils/date";

export default function CreateTaskForm() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    title: "",
    description: "",
    status: "pending",
    priority: "low",
    dueDate: "",
    category: "",
    reminderAt: "",
  });

  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    const payload = {
      title: formData.title,
      description: formData.description,
      status: formData.status,
      priority: formData.priority,
      due_date: formatDate(formData.dueDate),
      category: formData.category,
      reminder_at: formatDate(formData.reminderAt),
      completed_at: formatDate(formData.completedAt),
    };

  try {
      await createTask(payload);
      navigate("/tasks");
      setLoading(false);
    } catch (err) {
      console.error("Erro ao salvar:", err);
      setLoading(false);
    }
  };

  return (
    <motion.form
      onSubmit={handleSubmit}
      className="card border-0 shadow-sm p-4 rounded-4"
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
    >
      <div className="d-flex align-items-center mb-4">
        <i className="bi bi-clipboard-plus fs-4 text-primary me-2"></i>
        <h2 className="fs-4 fw-semibold mb-0">Nova Tarefa</h2>
      </div>

      <div className="mb-3">
        <label className="form-label fw-semibold">Título</label>
        <input
          type="text"
          name="title"
          className="form-control rounded-3"
          placeholder="Ex: Comprar ração"
          value={formData.title}
          onChange={handleChange}
          required
        />
      </div>

      <div className="mb-3">
        <label className="form-label fw-semibold">Descrição</label>
        <textarea
          name="description"
          className="form-control rounded-3"
          placeholder="Ex: Ir ao mercado comprar ração para as gatas"
          rows={3}
          value={formData.description}
          onChange={handleChange}
        />
      </div>

      <div className="row">
        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Status</label>
          <select
            name="status"
            className="form-select rounded-3"
            value={formData.status}
            onChange={handleChange}
          >
            <option value="pending">Pendente</option>
            <option value="in_progress">Em Progresso</option>
            <option value="done">Concluída</option>
          </select>
        </div>

        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Prioridade</label>
          <select
            name="priority"
            className="form-select rounded-3"
            value={formData.priority}
            onChange={handleChange}
          >
            <option value="low">Baixa</option>
            <option value="normal">Média</option>
            <option value="high">Alta</option>
          </select>
        </div>
      </div>

      <div className="row">
        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Data Limite</label>
          <input
            type="date"
            name="dueDate"
            className="form-control rounded-3"
            value={formData.dueDate}
            onChange={handleChange}
          />
        </div>

        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Categoria</label>
          <input
            type="text"
            name="category"
            className="form-control rounded-3"
            placeholder="Ex: Estudo, Trabalho..."
            value={formData.category}
            onChange={handleChange}
          />
        </div>
      </div>

      <div className="row">
        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Lembrete</label>
          <input
            type="datetime-local"
            name="reminderAt"
            className="form-control rounded-3"
            value={formData.reminderAt}
            onChange={handleChange}
          />
        </div>
      </div>

      <motion.button
        type="submit"
        whileHover={{ scale: 1.05 }}
        whileTap={{ scale: 0.95 }}
        className="btn btn-primary d-flex align-items-center justify-content-center gap-2"
        disabled={loading}
      >
        {loading ? (
          <>
            <i className="bi bi-arrow-repeat spinner-border spinner-border-sm me-1" role="status" />
            Salvando...
          </>
        ) : (
          <>
            <i className="bi bi-check-circle" />
            Criar Tarefa
          </>
        )}
      </motion.button>
    </motion.form>
  );
}
