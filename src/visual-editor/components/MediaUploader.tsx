"use client";

import { useRef, useState, DragEvent, ChangeEvent } from "react";
import { X, Image as ImageIcon, Video as VideoIcon } from "lucide-react";

interface MediaUploaderProps {
  label: string;
  value?: string;
  onChange: (file: File | null) => void;
  accept?: string;
  maxSizeMB?: number;
  type?: "image" | "video";
}

export function MediaUploader({
  label,
  value,
  onChange,
  accept = "image/*",
  maxSizeMB = 10,
  type = "image",
}: MediaUploaderProps) {
  const [error, setError] = useState<string | null>(null);
  const [isDragging, setIsDragging] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const validateFile = (file: File): string | null => {
    // Check file type
    const acceptedTypes = accept.split(",").map((t) => t.trim());
    const fileType = file.type;
    const isValidType = acceptedTypes.some((accepted) => {
      if (accepted.endsWith("/*")) {
        const prefix = accepted.split("/")[0];
        return fileType.startsWith(prefix + "/");
      }
      return fileType === accepted;
    });

    if (!isValidType) {
      return `Invalid file type. Accepted: ${accept}`;
    }

    // Check file size
    const maxSizeBytes = maxSizeMB * 1024 * 1024;
    if (file.size > maxSizeBytes) {
      return `File too large. Maximum size: ${maxSizeMB}MB`;
    }

    return null;
  };

  const handleFileSelect = (file: File) => {
    const validationError = validateFile(file);
    if (validationError) {
      setError(validationError);
      onChange(null);
      return;
    }

    setError(null);
    onChange(file);
  };

  const handleFileInputChange = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      handleFileSelect(file);
    }
  };

  const handleDragEnter = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDragging(true);
  };

  const handleDragLeave = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDragging(false);
  };

  const handleDragOver = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
  };

  const handleDrop = (e: DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDragging(false);

    const file = e.dataTransfer.files?.[0];
    if (file) {
      handleFileSelect(file);
    }
  };

  const handleRemove = () => {
    onChange(null);
    setError(null);
    if (fileInputRef.current) {
      fileInputRef.current.value = "";
    }
  };

  const handleChangeClick = () => {
    fileInputRef.current?.click();
  };

  const getFileNameFromUrl = (url: string): string => {
    try {
      const pathname = new URL(url).pathname;
      return pathname.split("/").pop() || "media file";
    } catch {
      return url.split("/").pop() || "media file";
    }
  };

  return (
    <div className="media-uploader-container">
      <label className="media-uploader-label">{label}</label>

      {value ? (
        <div className="media-preview-container">
          {type === "image" ? (
            <div className="media-preview-image-wrapper">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={value} alt="Preview" className="media-preview-image" />
            </div>
          ) : (
            <div className="media-preview-video-info">
              <VideoIcon size={24} className="media-preview-video-icon" />
              <span className="media-preview-filename">
                {getFileNameFromUrl(value)}
              </span>
            </div>
          )}

          <div className="media-preview-actions">
            <button
              type="button"
              onClick={handleChangeClick}
              className="media-change-button"
            >
              Change
            </button>
            <button
              type="button"
              onClick={handleRemove}
              className="media-remove-button"
              title="Remove media"
            >
              <X size={16} />
            </button>
          </div>
        </div>
      ) : (
        <div
          className={`media-upload-zone ${isDragging ? "dragging" : ""}`}
          onDragEnter={handleDragEnter}
          onDragLeave={handleDragLeave}
          onDragOver={handleDragOver}
          onDrop={handleDrop}
          onClick={handleChangeClick}
        >
          <div className="media-upload-icon">
            {type === "image" ? (
              <ImageIcon size={32} />
            ) : (
              <VideoIcon size={32} />
            )}
          </div>
          <p className="media-upload-text">
            {isDragging
              ? "Drop file here"
              : `Click to upload or drag and drop ${type}`}
          </p>
          <p className="media-upload-hint">Max size: {maxSizeMB}MB</p>
        </div>
      )}

      <input
        ref={fileInputRef}
        type="file"
        accept={accept}
        onChange={handleFileInputChange}
        className="media-file-input"
        aria-label={label}
      />

      {error && <div className="media-upload-error">{error}</div>}
    </div>
  );
}
