/**
 * MediaUploader Component - Sample Usage Examples
 *
 * This file demonstrates how to use the MediaUploader component
 * in different scenarios within the visual editor.
 */

"use client";

import { useState } from "react";
import { MediaUploader } from "./MediaUploader";

// Example 1: Simple Image Upload
export function ImageUploadExample() {
  const [imageUrl, setImageUrl] = useState<string | undefined>();

  const handleImageChange = (file: File | null) => {
    if (file) {
      // In a real scenario, you would upload the file to your server
      // and get back a URL. For now, we'll create a local URL.
      const url = URL.createObjectURL(file);
      setImageUrl(url);

      console.log("Selected image:", {
        name: file.name,
        size: file.size,
        type: file.type,
      });
    } else {
      setImageUrl(undefined);
    }
  };

  return (
    <MediaUploader
      label="Profile Image"
      value={imageUrl}
      onChange={handleImageChange}
      accept="image/*"
      maxSizeMB={5}
      type="image"
    />
  );
}

// Example 2: Video Upload
export function VideoUploadExample() {
  const [videoUrl, setVideoUrl] = useState<string | undefined>();

  const handleVideoChange = (file: File | null) => {
    if (file) {
      const url = URL.createObjectURL(file);
      setVideoUrl(url);

      console.log("Selected video:", {
        name: file.name,
        size: file.size,
        type: file.type,
      });
    } else {
      setVideoUrl(undefined);
    }
  };

  return (
    <MediaUploader
      label="Background Video"
      value={videoUrl}
      onChange={handleVideoChange}
      accept="video/*"
      maxSizeMB={50}
      type="video"
    />
  );
}

// Example 3: Specific File Types (JPG, PNG only)
export function SpecificTypesExample() {
  const [imageUrl, setImageUrl] = useState<string | undefined>();

  const handleImageChange = (file: File | null) => {
    if (file) {
      const url = URL.createObjectURL(file);
      setImageUrl(url);
    } else {
      setImageUrl(undefined);
    }
  };

  return (
    <MediaUploader
      label="Thumbnail (JPG/PNG only)"
      value={imageUrl}
      onChange={handleImageChange}
      accept="image/jpeg, image/png"
      maxSizeMB={2}
      type="image"
    />
  );
}

// Example 4: With Server Upload (Mock)
export function ServerUploadExample() {
  const [imageUrl, setImageUrl] = useState<string | undefined>();
  const [isUploading, setIsUploading] = useState(false);

  const handleImageChange = async (file: File | null) => {
    if (!file) {
      setImageUrl(undefined);
      return;
    }

    setIsUploading(true);

    try {
      // Mock server upload - replace with actual API call
      const formData = new FormData();
      formData.append("file", file);

      // Example API call (commented out):
      // const response = await fetch('/api/upload', {
      //   method: 'POST',
      //   body: formData,
      // })
      // const { url } = await response.json()
      // setImageUrl(url)

      // For demo, use local URL
      const url = URL.createObjectURL(file);
      setImageUrl(url);

      console.log("File uploaded successfully");
    } catch (error) {
      console.error("Upload failed:", error);
    } finally {
      setIsUploading(false);
    }
  };

  return (
    <div>
      <MediaUploader
        label="Upload to Server"
        value={imageUrl}
        onChange={handleImageChange}
        accept="image/*"
        maxSizeMB={10}
        type="image"
      />
      {isUploading && <p className="media-upload-hint">Uploading...</p>}
    </div>
  );
}

// Example 5: Within a Block Editor Form
export function BlockEditorExample() {
  const [formData, setFormData] = useState({
    title: "",
    description: "",
    image: undefined as string | undefined,
  });

  const handleImageChange = (file: File | null) => {
    if (file) {
      const url = URL.createObjectURL(file);
      setFormData((prev) => ({ ...prev, image: url }));
    } else {
      setFormData((prev) => ({ ...prev, image: undefined }));
    }
  };

  return (
    <div className="block-editor">
      <div className="editor-field">
        <label>Title</label>
        <input
          type="text"
          value={formData.title}
          onChange={(e) => setFormData({ ...formData, title: e.target.value })}
          className="editor-input"
        />
      </div>

      <div className="editor-field">
        <label>Description</label>
        <textarea
          value={formData.description}
          onChange={(e) =>
            setFormData({ ...formData, description: e.target.value })
          }
          className="editor-textarea"
        />
      </div>

      <MediaUploader
        label="Featured Image"
        value={formData.image}
        onChange={handleImageChange}
        accept="image/*"
        maxSizeMB={5}
        type="image"
      />
    </div>
  );
}
