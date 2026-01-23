"use client";

import { motion } from "framer-motion";
import { CheckCircle, XCircle, AlertCircle, Info, X } from "lucide-react";
import { useEffect, useState } from "react";

export type ToastType = "success" | "error" | "warning" | "info";

export interface ToastProps {
  id: string;
  type: ToastType;
  message: string;
  duration?: number;
  onDismiss: () => void;
}

const toastConfig = {
  success: {
    icon: CheckCircle,
    bg: "#D1F4E0",
    border: "#00C853",
    iconColor: "#00C853",
  },
  error: {
    icon: XCircle,
    bg: "#FFE5E5",
    border: "#FF3B30",
    iconColor: "#FF3B30",
  },
  warning: {
    icon: AlertCircle,
    bg: "#FFF4E5",
    border: "#FF9500",
    iconColor: "#FF9500",
  },
  info: {
    icon: Info,
    bg: "#E5F3FF",
    border: "#007AFF",
    iconColor: "#007AFF",
  },
};

export function Toast({
  id,
  type,
  message,
  duration = 3000,
  onDismiss,
}: ToastProps) {
  const [isPaused, setIsPaused] = useState(false);
  const [progress, setProgress] = useState(100);
  const config = toastConfig[type];
  const Icon = config.icon;

  useEffect(() => {
    if (isPaused) return;

    const interval = setInterval(() => {
      setProgress((prev) => {
        const newProgress = prev - (100 / duration) * 50; // Update every 50ms
        if (newProgress <= 0) {
          clearInterval(interval);
          onDismiss();
          return 0;
        }
        return newProgress;
      });
    }, 50);

    return () => clearInterval(interval);
  }, [duration, isPaused, onDismiss]);

  return (
    <motion.div
      initial={{ opacity: 0, x: 100, y: -20 }}
      animate={{ opacity: 1, x: 0, y: 0 }}
      exit={{ opacity: 0, x: 100 }}
      transition={{ type: "spring", damping: 25, stiffness: 300 }}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      style={{
        background: config.bg,
        borderLeft: `3px solid ${config.border}`,
      }}
      className="toast-item"
      role="alert"
      aria-live="polite"
      aria-atomic="true"
    >
      <div className="toast-content">
        <Icon
          size={20}
          strokeWidth={2}
          style={{ color: config.iconColor }}
          className="toast-icon"
        />
        <span className="toast-message">{message}</span>
        <button
          onClick={onDismiss}
          className="toast-dismiss"
          aria-label="Dismiss notification"
        >
          <X size={16} strokeWidth={2} />
        </button>
      </div>
      <motion.div
        className="toast-progress"
        style={{
          background: config.iconColor,
          width: `${progress}%`,
        }}
        initial={{ width: "100%" }}
        animate={{ width: isPaused ? `${progress}%` : "0%" }}
        transition={{
          duration: isPaused ? 0 : duration / 1000,
          ease: "linear",
        }}
      />
    </motion.div>
  );
}
