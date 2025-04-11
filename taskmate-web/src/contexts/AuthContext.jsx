import { createContext, useContext, useState, useEffect } from "react";
import { fetchUser, logout as logoutRequest } from "@/services/auth"; // importa aqui

const AuthContext = createContext();

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);

  useEffect(() => {
    async function loadUser() {
      const userData = await fetchUser();
      if (userData) setUser(userData);
    }

    loadUser();
  }, []);

  async function logout() {
    await logoutRequest();
    localStorage.removeItem("token");
    setUser(null);
    window.location.href = "/login";
  }

  return (
    <AuthContext.Provider value={{ user, setUser, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
