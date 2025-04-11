import { useAuth } from "@/contexts/AuthContext";
import { useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";

export default function Header() {
  const { user, logout } = useAuth(); // logout aqui
  const navigate = useNavigate();
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  useEffect(() => {
    const handleResize = () => setIsMobile(window.innerWidth < 768);
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const getInitials = (name) => {
    if (!name) return "";
    return name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase();
  };

  return (
    <nav className="navbar navbar-expand-md bg-light shadow-sm">
      <div className="container">
        <a className="navbar-brand" href="/">
          <img
            src={isMobile ? "/TaskMate_v1_logo.png" : "/TaskMate_v1_logo2.png"}
            alt="TaskMate"
            height="40"
          />
        </a>
        <button
          className="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#main-navbar"
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        <div className="collapse navbar-collapse" id="main-navbar">
          <div className="ms-auto d-flex align-items-center gap-2 flex-column flex-md-row">
            {user ? (
              <>
                <div
                  className="bg-primary text-white rounded-circle d-none align-items-center justify-content-center"
                  style={{
                    width: "40px",
                    height: "40px",
                    fontWeight: "bold",
                  }}
                >
                  {getInitials(user.name)}
                </div>
                <button
                  className="btn btn-outline-danger mt-2 mt-md-0"
                  onClick={() => logout()}
                >
                  Sair
                </button>
              </>
            ) : (
              <>
                <button
                  className="btn btn-outline-primary w-100 mt-2 mt-md-0"
                  onClick={() => navigate("/login")}
                >
                  Entrar
                </button>
                <button
                  className="btn btn-primary w-100 mt-2 mt-md-0"
                  onClick={() => navigate("/register")}
                >
                  Cadastrar
                </button>
              </>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
