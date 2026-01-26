"use client";

import { useState } from "react";
import Image from "next/image";
import { getAssetPath } from "@/lib/config";

interface TimelineEvent {
  year: string;
  title: string;
  description: string;
  image?: string;
}

interface Stat {
  value: string;
  label: string;
}

interface TeamMemberCardProps {
  name: string;
  title: string;
  subtitle?: string;
  image: string;
  bio: string;
  tags: string[];
  stats?: Stat[];
  timeline: TimelineEvent[];
}

export default function TeamMemberCard({
  name,
  title,
  subtitle,
  image,
  bio,
  tags,
  stats,
  timeline,
}: TeamMemberCardProps) {
  const [activeIndex, setActiveIndex] = useState(0);
  const [isTimelineOpen, setIsTimelineOpen] = useState(false);

  return (
    <div className="bg-white rounded-2xl p-6 md:p-8 shadow-sm">
      {/* Profilbild und Info */}
      <div className="text-center mb-6">
        <div className="w-28 h-28 mx-auto rounded-full overflow-hidden bg-[#D4F4E8] relative mb-4">
          <Image
            src={getAssetPath(image)}
            alt={name}
            fill
            className="object-cover object-top scale-150"
            sizes="112px"
          />
        </div>
        <h3
          className="text-xl font-semibold text-[#1a1a1a] mb-0.5"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          {name}
        </h3>
        <p
          className="text-[#0D7A5F] font-medium text-sm"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          {title}
        </p>
        {subtitle && (
          <p
            className="text-[#999] text-xs mt-0.5"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            {subtitle}
          </p>
        )}
      </div>

      {/* Stats - Mini */}
      {stats && stats.length > 0 && (
        <div className="flex justify-center gap-8 mb-5">
          {stats.map((stat) => (
            <div key={stat.label} className="text-center">
              <div
                className="text-xl font-bold text-[#0D7A5F]"
                style={{ fontFamily: "'Poppins', sans-serif" }}
              >
                {stat.value}
              </div>
              <p className="text-xs text-[#666]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                {stat.label}
              </p>
            </div>
          ))}
        </div>
      )}

      {/* Bio */}
      <p
        className="text-[#555] text-sm leading-relaxed mb-5 text-center"
        style={{ fontFamily: "'Poppins', sans-serif" }}
      >
        {bio}
      </p>

      {/* Tags */}
      <div className="flex flex-wrap justify-center gap-2 mb-5">
        {tags.map((tag) => (
          <span
            key={tag}
            className="px-3 py-1 bg-[#f5f5f5] text-[#666] text-xs rounded-full"
          >
            {tag}
          </span>
        ))}
      </div>

      {/* Timeline Toggle Button */}
      <button
        onClick={() => setIsTimelineOpen(!isTimelineOpen)}
        className="w-full flex items-center justify-center gap-2 py-3 text-sm font-medium text-[#0D7A5F] hover:bg-[#f5f5f5] rounded-lg transition-colors border border-[#e5e5e5]"
        style={{ fontFamily: "'Poppins', sans-serif" }}
      >
        <span>Mein Weg</span>
        <svg
          className={`w-4 h-4 transition-transform duration-300 ${isTimelineOpen ? "rotate-180" : ""}`}
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      {/* Animierte Timeline */}
      <div
        className={`overflow-hidden transition-all duration-500 ease-in-out ${
          isTimelineOpen ? "max-h-[600px] opacity-100 mt-6" : "max-h-0 opacity-0"
        }`}
      >
        {/* Timeline Progress Bar */}
        <div className="relative h-1 bg-[#e5e5e5] rounded-full mb-4">
          <div
            className="absolute top-0 left-0 h-full bg-[#0D7A5F] rounded-full transition-all duration-500 ease-out"
            style={{ width: `${((activeIndex + 1) / timeline.length) * 100}%` }}
          />
          {/* Punkte auf der Linie */}
          {timeline.map((event, index) => (
            <button
              key={index}
              onClick={() => setActiveIndex(index)}
              className={`absolute top-1/2 w-3 h-3 rounded-full border-2 transition-all duration-300 ${
                index <= activeIndex
                  ? "bg-[#0D7A5F] border-[#0D7A5F]"
                  : "bg-white border-[#ccc] hover:border-[#0D7A5F]"
              }`}
              style={{ left: `${(index / (timeline.length - 1)) * 100}%`, transform: "translate(-50%, -50%)" }}
              title={event.year}
            />
          ))}
        </div>

        {/* Jahre unter den Punkten */}
        <div className="flex justify-between mb-4 px-1">
          {timeline.map((event, index) => (
            <button
              key={event.year}
              onClick={() => setActiveIndex(index)}
              className={`text-xs transition-colors ${
                activeIndex === index ? "text-[#0D7A5F] font-semibold" : "text-[#999]"
              }`}
              style={{ fontFamily: "'Poppins', sans-serif" }}
            >
              {event.year}
            </button>
          ))}
        </div>

        {/* Timeline Content - mit Animation */}
        <div className="relative overflow-hidden min-h-[220px]">
          {timeline.map((event, index) => (
            <div
              key={event.year}
              className={`absolute inset-0 transition-all duration-400 ease-in-out ${
                activeIndex === index
                  ? "opacity-100 translate-x-0"
                  : index < activeIndex
                  ? "opacity-0 -translate-x-8"
                  : "opacity-0 translate-x-8"
              }`}
            >
              {/* Bild */}
              <div className="w-full aspect-[16/10] bg-[#f5f5f5] rounded-lg overflow-hidden relative mb-3">
                {event.image ? (
                  <Image
                    src={getAssetPath(event.image)}
                    alt={event.title}
                    fill
                    className="object-cover"
                    sizes="400px"
                  />
                ) : (
                  <div className="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                    {event.title}
                  </div>
                )}
              </div>

              {/* Text */}
              <div className="text-center">
                <h4
                  className="text-base font-semibold text-[#1a1a1a] mb-1"
                  style={{ fontFamily: "'Poppins', sans-serif" }}
                >
                  {event.title}
                </h4>
                <p
                  className="text-[#666] text-sm"
                  style={{ fontFamily: "'Poppins', sans-serif" }}
                >
                  {event.description}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* Navigation Pfeile */}
        <div className="flex justify-between items-center mt-2">
          <button
            onClick={() => setActiveIndex(Math.max(0, activeIndex - 1))}
            disabled={activeIndex === 0}
            className={`flex items-center gap-1 px-3 py-1.5 rounded text-sm transition-all ${
              activeIndex === 0
                ? "text-[#ccc] cursor-not-allowed"
                : "text-[#0D7A5F] hover:bg-[#f5f5f5]"
            }`}
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
            <span className="hidden sm:inline">Zurück</span>
          </button>

          <span className="text-xs text-[#999]">
            {activeIndex + 1} / {timeline.length}
          </span>

          <button
            onClick={() => setActiveIndex(Math.min(timeline.length - 1, activeIndex + 1))}
            disabled={activeIndex === timeline.length - 1}
            className={`flex items-center gap-1 px-3 py-1.5 rounded text-sm transition-all ${
              activeIndex === timeline.length - 1
                ? "text-[#ccc] cursor-not-allowed"
                : "text-[#0D7A5F] hover:bg-[#f5f5f5]"
            }`}
          >
            <span className="hidden sm:inline">Weiter</span>
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  );
}
