import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import RegisterForm from '@/components/RegisterForm/RegisterForm';
import api from '@/lib/api';
import styles from './Register.module.css';
import { motion } from 'framer-motion';

export default function Register() {
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const navigate = useNavigate();

  const handleRegister = async ({ name, email, password, confirmPassword }) => {
    setError(null);
    setSuccess(null);

    try {
      const { data } = await api.post('/register', {
        name,
        email,
        password,
        confirmPassword,
      });

      localStorage.setItem('token', data.token);
      setSuccess(data.message);
      navigate('/');
    } catch (err) {
      console.error(err);
      setError(err.response?.data?.message || 'Erro ao tentar criar uma conta');
    }
  };

  return (
    <motion.div
      className={styles.container}
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.6 }}
    >
      <RegisterForm onSubmit={handleRegister} success={success} error={error} />
      <div className="text-center mt-3">
        <Link to="/" className="btn btn-link text-decoration-none">
          <i className="bi bi-arrow-left me-1"></i> Voltar para Home
        </Link>
      </div>
    </motion.div>
  );
}
