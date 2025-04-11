import { Outlet, Navigate } from "react-router-dom";

export default function PrivateLayout() {
  const isAuthenticated = !!localStorage.getItem("token");

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  return <Outlet />;
}
