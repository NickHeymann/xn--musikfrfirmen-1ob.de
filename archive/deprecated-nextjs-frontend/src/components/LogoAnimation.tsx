"use client";

import { useEffect, useRef, useState } from "react";

interface MusicNote {
  id: number;
  note: string;
  x: number;
}

export default function LogoAnimation() {
  const [isAnimated, setIsAnimated] = useState(false);
  const [musicNotes, setMusicNotes] = useState<MusicNote[]>([]);
  const logoRef = useRef<HTMLDivElement>(null);
  const noteIdRef = useRef(0);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
            setIsAnimated(false);
            if (logoRef.current) {
              void logoRef.current.offsetWidth;
            }
            setIsAnimated(true);
          } else if (!entry.isIntersecting) {
            setIsAnimated(false);
          }
        });
      },
      {
        threshold: [0, 0.5],
        rootMargin: '0px',
      }
    );

    if (logoRef.current) {
      observer.observe(logoRef.current);
    }

    return () => observer.disconnect();
  }, []);

  const handleMouseEnter = () => {
    setIsAnimated(false);
    if (logoRef.current) {
      void logoRef.current.offsetWidth;
    }
    setIsAnimated(true);
    createMusicNotes();
  };

  const createMusicNotes = () => {
    const notes = ['♪', '♫', '♬', '♩'];
    const numberOfNotes = 6;
    const newNotes: MusicNote[] = [];

    for (let i = 0; i < numberOfNotes; i++) {
      const randomNote = notes[Math.floor(Math.random() * notes.length)];
      const randomX = (Math.random() - 0.5) * 200;

      newNotes.push({
        id: noteIdRef.current++,
        note: randomNote,
        x: randomX,
      });
    }

    newNotes.forEach((note, index) => {
      setTimeout(() => {
        setMusicNotes(prev => [...prev, note]);

        setTimeout(() => {
          setMusicNotes(prev => prev.filter(n => n.id !== note.id));
        }, 2000);
      }, index * 150);
    });
  };

  return (
    <div className="mff-logo-wrapper flex justify-center items-center w-full relative z-[1]">
      <div
        ref={logoRef}
        className={`mff-logo-elegant relative inline-flex flex-col items-center cursor-default overflow-visible ${isAnimated ? 'mff-animated' : ''}`}
        onMouseEnter={handleMouseEnter}
      >
        <div
          className={`mff-logo-elegant-text text-[42px] font-semibold relative inline-block leading-none ${isAnimated ? 'animate-metallic-swoosh' : ''}`}
          style={{
            fontFamily: "'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
            background: 'linear-gradient(90deg, #000 0%, #000 45%, #B2EAD8 49%, #D4F4E8 50%, #B2EAD8 51%, #000 55%, #000 100%)',
            backgroundSize: '300% 100%',
            backgroundPosition: isAnimated ? '-200% 0' : '0% 0',
            WebkitBackgroundClip: 'text',
            backgroundClip: 'text',
            WebkitTextFillColor: 'transparent',
            transition: isAnimated ? 'background-position 1.9s ease-out' : 'none',
          }}
        >
          musikfürfirmen.de
        </div>

        <div
          className={`mff-tagline-elegant text-lg font-light text-[#666] tracking-[0.5px] relative inline-block whitespace-nowrap leading-[1.4] cursor-default ${isAnimated ? 'animate-tagline-appear' : 'mff-tagline-hidden'}`}
          style={{
            fontFamily: "'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
          }}
        >
          Dein Partner für Firmenevents
          <span
            className={`absolute bottom-[-4px] left-0 w-full h-[2px] bg-[#d4f4e8] ${isAnimated ? 'animate-underline-appear' : 'opacity-0'}`}
          />
        </div>

        {musicNotes.map((note) => (
          <div
            key={note.id}
            className="mff-music-note absolute text-xl text-[#B2EAD8] pointer-events-none z-10 animate-note-float"
            style={{
              left: `calc(50% + ${note.x}px)`,
              top: '50%',
            }}
          >
            {note.note}
          </div>
        ))}
      </div>

      <style jsx>{`
        @keyframes metallic-swoosh {
          0% { background-position: 0% 0; }
          100% { background-position: -200% 0; }
        }

        .animate-metallic-swoosh {
          animation: metallic-swoosh 1.9s ease-out forwards;
        }

        .mff-tagline-hidden {
          opacity: 0;
          transform: translateY(-10px);
        }

        @keyframes tagline-appear {
          0% { opacity: 0; transform: translateY(-10px); }
          100% { opacity: 1; transform: translateY(0); }
        }

        .animate-tagline-appear {
          opacity: 0;
          transform: translateY(-10px);
          animation: tagline-appear 0.8s ease 1.0s forwards;
        }

        @keyframes underline-appear {
          0% { opacity: 0; }
          100% { opacity: 1; }
        }

        .animate-underline-appear {
          animation: underline-appear 1.4s cubic-bezier(0.4, 0, 0.2, 1) 1.2s forwards;
        }

        @keyframes note-float {
          0% {
            opacity: 1;
            transform: translateY(0) rotate(0deg);
          }
          100% {
            opacity: 0;
            transform: translateY(-80px) rotate(20deg);
          }
        }

        .animate-note-float {
          animation: note-float 2s ease-out forwards;
        }

        @media (max-width: 768px) {
          .mff-logo-elegant-text {
            font-size: 32px !important;
          }
          .mff-tagline-elegant {
            font-size: 15px !important;
          }
          .mff-music-note {
            font-size: 16px !important;
          }
        }

        @media (max-width: 480px) {
          .mff-logo-elegant-text {
            font-size: 24px !important;
          }
          .mff-tagline-elegant {
            font-size: 13px !important;
          }
          .mff-music-note {
            font-size: 14px !important;
          }
        }
      `}</style>
    </div>
  );
}
