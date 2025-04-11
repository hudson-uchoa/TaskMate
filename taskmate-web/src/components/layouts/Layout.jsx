import Sidebar from '../components/Sidebar';

const Layout = ({ children }) => {
  return (
    <div className="d-flex">
      <Sidebar />
      <div className="flex-grow-1" style={{ marginLeft: '220px' }}>
        <header className="bg-white border-bottom p-3">
          <h4 className="m-0">Dashboard</h4>
        </header>
        <main className="p-4">{children}</main>
      </div>
    </div>
  );
};

export default Layout;
