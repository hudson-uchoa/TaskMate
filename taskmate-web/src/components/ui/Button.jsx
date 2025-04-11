import { motion } from "framer-motion";

export function Button({ children, className = "", ...props }) {
  return (
    <motion.button
      whileTap={{ scale: 0.95 }}
      whileHover={{ scale: 1.02 }}
      className={`bg-blue-600 text-white font-semibold py-2 px-4 rounded-xl shadow-sm transition ${className}`}
      {...props}
    >
      {children}
    </motion.button>
  );
}
