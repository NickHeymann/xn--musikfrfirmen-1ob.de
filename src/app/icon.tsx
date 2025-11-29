import { ImageResponse } from "next/og";

export const runtime = "edge";
export const size = { width: 32, height: 32 };
export const contentType = "image/png";

export default function Icon() {
  return new ImageResponse(
    (
      <div
        style={{
          width: "100%",
          height: "100%",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          backgroundColor: "#D4F4E8",
          borderRadius: "50%",
        }}
      >
        <span
          style={{
            fontSize: 20,
            fontWeight: 600,
            color: "#0D7A5F",
          }}
        >
          m
        </span>
      </div>
    ),
    { ...size }
  );
}
