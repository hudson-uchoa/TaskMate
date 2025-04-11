import { useState } from 'react';
import { Link } from 'react-router-dom';
import styles from './LoginForm.module.css';

export default function LoginForm({ onSubmit, success, error }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    await onSubmit({ email, password });
    setLoading(false);
  };

  return (
    <div className={`container d-flex justify-content-center align-items-center ${styles.fullHeight}`}>
      <div className={styles.formWrapper}>
        <form onSubmit={handleSubmit} className="bg-white p-4 p-sm-5 rounded shadow-sm">

          <div className="text-center mb-4">
            <img
              src="/TaskMate_v2_logo.png"
              alt="Logo TaskMate"
              className={`${styles.logo}`}
            />
          </div>

          {success && <div className="alert alert-success">{success}</div>}

          {error && <div className="alert alert-danger">{error}</div>}

          <div className="mb-3">
            <label htmlFor="email" className="form-label">E-mail</label>
            <div className="input-group">
              <span className="input-group-text bg-light"><i className="bi bi-envelope"></i></span>
              <input
                type="email"
                className="form-control"
                id="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
            </div>
          </div>

          <div className="mb-4">
            <label htmlFor="password" className="form-label">Senha</label>
            <div className="input-group">
              <span className="input-group-text bg-light"><i className="bi bi-lock"></i></span>
              <input
                type={showPassword ? "text" : "password"}
                className="form-control"
                id="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
              <button
                type="button"
                className="btn-outline-secondary input-group-text bg-light"
                onClick={() => setShowPassword((prev) => !prev)}
                tabIndex={-1}
              >
                <i className={`bi ${showPassword ? "bi-eye" : "bi-eye-slash"}`}></i>
              </button>
            </div>
          </div>

          <button type="submit" className="btn btn-primary w-100 d-flex align-items-center justify-content-center" disabled={loading}>
            {loading ? (
              <>
                <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Entrando...
              </>
            ) : (
              <>
                <i className="bi bi-box-arrow-in-right me-2 "></i>
                Entrar
              </>
            )}
          </button>

          <div className="text-center mt-3">
            <Link to="/register" className="text-decoration-none">
              Não tem conta? <strong>Registre-se</strong>
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
}
