import HomeGuest from './HomeGuest';
import HomeDashboard from './HomeDashboard';

export default function Home() {
  const isAuthenticated = !!localStorage.getItem("token");

  return isAuthenticated ? <HomeDashboard /> : <HomeGuest />;
}
