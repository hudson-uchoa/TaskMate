import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HomePage from "@/pages/Home/Home";
import LoginPage from "@/pages/Auth/Login";
import RegisterPage from "@/pages/Auth/Register";
import TasksPage from "@/pages/Tasks/Index";
import CreateTaskPage from "@/pages/Tasks/Create";
import EditTaskPage from "@/pages/Tasks/Edit";
import BaseLayout from "@/routes/BaseLayout";
import PrivateLayout from "@/routes/PrivateLayout";
import AuthLayout from "@/routes/AuthLayout";

export default function App() {
  return (
    <Router>
      <Routes>
        <Route element={<AuthLayout />}>
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
        </Route>

        <Route element={<BaseLayout />}>
            <Route path="/" element={<HomePage />} />
          <Route element={<PrivateLayout />}>
            <Route path="/tasks" element={<TasksPage />} />
            <Route path="/tasks/create" element={<CreateTaskPage />} />
            <Route path="/tasks/edit/:id" element={<EditTaskPage />} />
          </Route>
        </Route>
      </Routes>
    </Router>
  );
}