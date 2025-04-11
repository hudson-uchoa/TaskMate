import { Outlet } from "react-router-dom";
import Header from "@/components/layouts/Header";

export default function BaseLayout() {
  return (
    <>
      <Header />
      <main className="container mt-4">
        <Outlet />
      </main>
    </>
  );
}
