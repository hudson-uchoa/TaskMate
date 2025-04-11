import { Link } from 'react-router-dom';

const Sidebar = () => {
  return (
    <aside className="bg-light border-end vh-100 p-3 position-fixed" style={{ width: '220px' }}>
      <h5 className="mb-4">TaskMate</h5>
      <ul className="nav flex-column">
        <li className="nav-item">
          <Link className="nav-link active" to="/home">Tarefas</Link>
        </li>
        {/* Adicione mais links depois */}
      </ul>
    </aside>
  );
};

export default Sidebar;
