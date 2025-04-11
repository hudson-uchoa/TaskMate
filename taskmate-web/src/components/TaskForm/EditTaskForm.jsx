import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { motion } from "framer-motion";
import { getTaskById, updateTask } from "@/services/task";
import { formatDate, convertToDatetimeLocal, formatToDateInput } from "@/utils/date";

export default function EditTaskForm() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    title: "",
    description: "",
    status: "pending",
    priority: "normal",
    dueDate: "",
    category: "",
    reminderAt: "",
    completedAt: "",
  });

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    const fetchTask = async () => {
      try {
        const data = await getTaskById(id);
        setFormData({
          title: data.data.task.title || "",
          description: data.data.task.description || "",
          status: data.data.task.status || "pending",
          priority: data.data.task.priority || "normal",
          dueDate: formatToDateInput(data.data.task.due_date) || "",
          category: data.data.task.category || "",
          reminderAt: convertToDatetimeLocal(data.data.task.reminder_at) || "",
          completedAt: convertToDatetimeLocal(data.data.task.completed_at) || "",
        });
        setLoading(false);
      } catch (error) {
        console.error("Erro ao buscar tarefa:", error);
      }
    };
    fetchTask();
  }, [id]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);

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
    console.log(payload);

    try {
      await updateTask(id, payload);
      navigate("/tasks");
    } catch (error) {
      console.error("Erro ao atualizar tarefa:", error);
      setSaving(false);
    }
  };

  if (loading) return <div className="text-center mt-5">Carregando tarefa...</div>;

  return (
    <motion.form
      onSubmit={handleSubmit}
      className="card border-0 shadow-sm p-4 rounded-4"
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
    >
      <div className="d-flex align-items-center mb-4">
        <i className="bi bi-pencil-square fs-4 text-warning me-2"></i>
        <h2 className="fs-4 fw-semibold mb-0">Editar Tarefa</h2>
      </div>

      <div className="mb-3">
        <label className="form-label fw-semibold">Título</label>
        <input
          type="text"
          name="title"
          className="form-control rounded-3"
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

        <div className="col-md-6 mb-3">
          <label className="form-label fw-semibold">Concluído em</label>
          <input
            type="datetime-local"
            name="completedAt"
            className="form-control rounded-3"
            value={formData.completedAt}
            onChange={handleChange}
          />
        </div>
      </div>

      <motion.button
        type="submit"
        whileHover={{ scale: 1.05 }}
        whileTap={{ scale: 0.95 }}
        className="btn btn-warning d-flex align-items-center justify-content-center gap-2"
        disabled={saving}
      >
        {saving ? (
          <>
            <i className="bi bi-arrow-repeat spinner-border spinner-border-sm me-1" role="status" />
            Salvando...
          </>
        ) : (
          <>
            <i className="bi bi-save" />
            Salvar Alterações
          </>
        )}
      </motion.button>
    </motion.form>
  );
}
