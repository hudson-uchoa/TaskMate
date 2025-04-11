import styles from './Login.module.css';
import LoginForm from '@/components/LoginForm/LoginForm';
import api from '@/lib/api';
import { useState } from 'react';
import { useNavigate, Link } from "react-router-dom";
import { motion } from "framer-motion";

export default function Login() {
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const navigate = useNavigate();

  const handleLogin = async ({ email, password }) => {
    setError(null);
    setSuccess(null);
    try {
      const response = await api.post('/login', { email, password });
      const { token, message } = response.data;

      localStorage.setItem('token', token);
      setSuccess(message);
      navigate("/");
    } catch (err) {
      console.error(err);
      setError(err.response?.data?.message || 'Erro ao tentar fazer login');
    }
  };

  return (
    <motion.div
      className={styles.container}
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.6 }}
    >
      <LoginForm onSubmit={handleLogin} success={success} error={error} />
      <div className="text-center mt-3">
        <Link to="/" className="btn btn-link text-decoration-none">
          <i className="bi bi-arrow-left me-1"></i> Voltar para Home
        </Link>
      </div>
    </motion.div>
  );
}
