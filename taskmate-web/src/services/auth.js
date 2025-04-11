import api from "@/lib/api";

export function getToken() {
    return localStorage.getItem("token");
}

export async function fetchUser() {
    const token = getToken();
    if (!token) return null;

    try {
        const response = await api.get("me", {
        headers: {
            Authorization: `Bearer ${token}`,
        },
        });

        return response.data;
    } catch (error) {
        console.error("Erro ao buscar usuário:", error);
        return null;
    }
}

export async function logout() {
    const token = getToken();
    if (!token) return;
  
    try {
      await api.post("logout", null, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
  
      localStorage.removeItem("token");
    } catch (error) {
      console.error("Erro ao fazer logout:", error);
    }
  }
