/**
 * Spinner Component - Apple-quality loading indicator
 * Used for async operations (save, upload, data loading)
 */

interface SpinnerProps {
  size?: "sm" | "md" | "lg"; // 16px, 24px, 32px
  color?: string; // Default: #007AFF
  className?: string;
}

export function Spinner({
  size = "md",
  color = "#007AFF",
  className = "",
}: SpinnerProps) {
  const sizeMap = {
    sm: 16,
    md: 24,
    lg: 32,
  };

  const pixels = sizeMap[size];

  return (
    <div
      className={`spinner ${className}`}
      style={{
        width: pixels,
        height: pixels,
        borderColor: `${color}33`, // 20% opacity for border
        borderTopColor: color,
      }}
      role="status"
      aria-label="Loading"
    />
  );
}
