import { useState, useRef, useEffect } from "react";
import DoctorDetails from "@/imports/DoctorDetails/index";
import AddNewDoctor from "@/imports/AddNewDoctor/index";
import DoctorGrid from "@/imports/DoctorGrid/index";
import AddPatient from "@/imports/AddPatient/index";
import PatientDetails from "@/imports/PatientDetails/index";
import PatientGrid from "@/imports/PatientGrid/index";
import Appointment from "@/imports/Appointment/index";
import imgAvatarCircle from "@/imports/Appointment/d3a208331b29eccdbc470584100d552302c63856.png";

type ViewId =
  | "dashboard"
  | "applications"
  | "frontend"
  | "layouts"
  | "doctor-grid"
  | "doctor-details"
  | "add-doctor"
  | "doctor-schedule"
  | "patient-grid"
  | "patient-details"
  | "create-patient"
  | "appointments"
  | "new-appointment"
  | "calendar"
  | "locations"
  | "services"
  | "specializations"
  | "assets"
  | "activities"
  | "messages"
  | "staffs"
  | "departments"
  | "designations"
  | "attendance"
  | "leaves"
  | "leave-type"
  | "holidays"
  | "payroll"
  | "expenses"
  | "income"
  | "invoices"
  | "payments"
  | "transactions";

const VIEW_HEIGHTS: Record<string, number> = {
  "doctor-details": 1520,
  "add-doctor": 1200,
  "doctor-grid": 1160,
  "patient-grid": 1120,
  "patient-details": 1200,
  "create-patient": 1200,
  appointments: 1040,
};

function getViewHeight(view: ViewId) {
  return VIEW_HEIGHTS[view] ?? 900;
}

function CollapsibleSection({
  open,
  children,
}: {
  open: boolean;
  children: React.ReactNode;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const [height, setHeight] = useState(0);

  useEffect(() => {
    if (ref.current) {
      setHeight(ref.current.scrollHeight);
    }
  }, [children]);

  return (
    <div
      style={{
        overflow: "hidden",
        maxHeight: open ? `${height + 8}px` : "0px",
        transition: "max-height 0.25s cubic-bezier(0.4,0,0.2,1)",
      }}
    >
      <div ref={ref}>{children}</div>
    </div>
  );
}

// SVG Icons matching the design exactly
function IconDashboard() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="0.5" y="0.5" width="5" height="5.5" rx="0.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="0.5" y="8.5" width="5" height="5" rx="0.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="8.5" y="0.5" width="5" height="5" rx="0.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="8.5" y="8.5" width="5" height="5.5" rx="0.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconApps() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="0.5" y="0.5" width="5.5" height="5.5" rx="1" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="8" y="0.5" width="5.5" height="5.5" rx="1" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="0.5" y="8" width="5.5" height="5.5" rx="1" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <rect x="8" y="8" width="5.5" height="5.5" rx="1" fill="#2E37A4" stroke="#2E37A4" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconGlobe() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="7" cy="7" r="5.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 5.25H12.5M1.5 8.75H12.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M7 1.5C7 1.5 5 4 5 7C5 10 7 12.5 7 12.5C7 12.5 9 10 9 7C9 4 7 1.5 7 1.5Z" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconSidebar() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="1.5" width="11" height="11" rx="1" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5.25 1.5V12.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconUserPlus() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="5.5" cy="4" r="2.5" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1 12.5C1 10.5 3 9 5.5 9" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M10.5 9V12.5M8.75 10.75H12.25" stroke="#0A1B39" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconUserHeart({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="5.5" cy="4" r="2.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1 12.5C1 10.5 3 9 5.5 9" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M9.5 10.5C9.5 10.5 7.5 9 7.5 7.75C7.5 7 8 6.5 8.75 6.5C9.1 6.5 9.5 6.75 9.5 6.75C9.5 6.75 9.9 6.5 10.25 6.5C11 6.5 11.5 7 11.5 7.75C11.5 9 9.5 10.5 9.5 10.5Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconCalendar({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="2.5" width="11" height="10" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 6.5H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 1.5V3.5M9.5 1.5V3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5.5 9.5L6.5 10.5L8.5 8.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconMapPin({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M7 1C4.79 1 3 2.79 3 5C3 8 7 13 7 13C7 13 11 8 11 5C11 2.79 9.21 1 7 1Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="7" cy="5" r="1.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconServices({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="5.5" cy="4" r="2.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1 12.5C1 10.5 3 9 5.5 9H6.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="10.5" cy="11" r="1.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M10.5 8V9.5M10.5 12.5V14M8 11H9.5M11.5 11H13" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconStethoscope({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M3 1.5V5C3 6.66 4.34 8 6 8C7.66 8 9 6.66 9 5V1.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M2.5 1.5V3.5M9.5 1.5V3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M9 8C9 8 9 10 10.5 11C12 12 12.5 11 12.5 11" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="11" cy="11.5" r="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconAsset({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="5.5" y="5.5" width="8" height="8" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M0.5 8.5V1.5C0.5 1 1 0.5 1.5 0.5H8.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconActivity({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <polyline points="1,7 4,4 6,9 9,2 11,7 13,7" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconMessages({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M12.5 9.5C12.5 10 12 10.5 11.5 10.5H3.5L1.5 12.5V2.5C1.5 2 2 1.5 2.5 1.5H11.5C12 1.5 12.5 2 12.5 2.5V9.5Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconUsers({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="5.5" cy="4.5" r="2" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1 12.5C1 10.5 3 9 5.5 9C8 9 10 10.5 10 12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="10.5" cy="4.5" r="1.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M12.5 9C12.5 9 12 8.5 10.5 8.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconBuilding({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="1.5" width="11" height="11" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5 12.5V7.5H9V12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <rect x="3.5" y="3.5" width="2" height="2" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <rect x="8.5" y="3.5" width="2" height="2" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconDesignation({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="3.5" width="11" height="7" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 3.5V2.5C4.5 2 5 1.5 5.5 1.5H8.5C9 1.5 9.5 2 9.5 2.5V3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 7H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconAttendance({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="2.5" width="11" height="10" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 6.5H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 1.5V3.5M9.5 1.5V3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 9.5L6 11L9.5 8" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconLeaves({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M2.5 12.5C2.5 12.5 2.5 7 7 4C11.5 1 13 2.5 13 2.5C13 2.5 13 8 7 10C4.5 11 2.5 12.5 2.5 12.5Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M2.5 12.5C2.5 12.5 5 9 8 8" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconHolidays({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="2.5" width="11" height="10" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 6.5H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 1.5V3.5M9.5 1.5V3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="5" cy="9.5" r="0.75" fill={color} />
      <circle cx="7" cy="9.5" r="0.75" fill={color} />
      <circle cx="9" cy="9.5" r="0.75" fill={color} />
    </svg>
  );
}

function IconPayroll({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="3.5" width="11" height="7" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="7" cy="7" r="1.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4.5 5.5H1.5M4.5 8.5H1.5M9.5 5.5H12.5M9.5 8.5H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconExpenses({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M1.5 3.5H12.5V11.5C12.5 12 12 12.5 11.5 12.5H2.5C2 12.5 1.5 12 1.5 11.5V3.5Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 3.5L3 1.5H11L12.5 3.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <circle cx="7" cy="8" r="2" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconIncome({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <polyline points="1,10 4.5,6.5 7,8.5 10,4 13,5.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <polyline points="10,2 13,2 13,5.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconInvoices({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M3.5 1.5H10.5C11 1.5 11.5 2 11.5 2.5V12.5L9.5 11.5L7 12.5L4.5 11.5L2.5 12.5V2.5C2.5 2 3 1.5 3.5 1.5Z" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5 5H9M5 7H9M5 9H7" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconPayments({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1.5" y="3" width="11" height="8" rx="1" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M1.5 5.5H12.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M4 8.5H6" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function IconTransactions({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M2 9.5H12M10 7.5L12 9.5L10 11.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M12 4.5H2M4 2.5L2 4.5L4 6.5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function ChevronRight({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M5 3L9 7L5 11" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function ChevronDown({ color = "#0A1B39" }: { color?: string }) {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M3 5L7 9L11 5" stroke={color} strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function PreclinicLogo() {
  return (
    <div className="flex items-center gap-[8px]">
      <div
        className="flex items-center justify-center rounded-[8px] shrink-0"
        style={{
          width: 36,
          height: 36,
          background: "linear-gradient(135deg, #2e37a4 60%, #27AE60 100%)",
        }}
      >
        <span
          style={{
            fontFamily: "'Inter', sans-serif",
            fontWeight: 700,
            fontSize: 16,
            color: "#fff",
            letterSpacing: -1,
          }}
        >
          P
        </span>
      </div>
      <span
        style={{
          fontFamily: "'Inter', sans-serif",
          fontWeight: 700,
          fontSize: 16,
          color: "#0a1b39",
          letterSpacing: -0.5,
        }}
      >
        Preclinic
      </span>
    </div>
  );
}

function CollapseIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <rect x="1" y="1" width="12" height="12" rx="2" stroke="#6c7688" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M5 1V13" stroke="#6c7688" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function SearchIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <circle cx="6.5" cy="6.5" r="4.5" stroke="#9DA4B0" strokeLinecap="round" strokeLinejoin="round" />
      <path d="M10 10L12.5 12.5" stroke="#9DA4B0" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

function KbdIcon() {
  return (
    <span
      style={{
        fontFamily: "'Inter', sans-serif",
        fontSize: 11,
        color: "#9DA4B0",
        background: "#f5f6f8",
        borderRadius: 4,
        padding: "1px 4px",
        border: "1px solid #e7e8eb",
      }}
    >
      ⌘
    </span>
  );
}

// The tree branch indicator for sub-menu items
function TreeBullet({ active }: { active?: boolean }) {
  return (
    <div
      className="shrink-0"
      style={{ width: 20, height: 32, position: "relative" }}
    >
      <svg
        width="20"
        height="32"
        viewBox="0 0 20 32"
        fill="none"
        style={{ position: "absolute", inset: 0 }}
      >
        <line
          x1="10"
          x2="10"
          y1="0"
          y2="32"
          stroke="#E7E8EB"
          strokeWidth="1"
        />
        <circle
          cx="10"
          cy="16"
          r="7"
          fill="white"
        />
        <circle
          cx="10"
          cy="16"
          r="3.5"
          fill={active ? "#2E37A4" : "#E7E8EB"}
          stroke="#E7E8EB"
        />
      </svg>
    </div>
  );
}

interface SidebarProps {
  activeView: ViewId;
  onNavigate: (view: ViewId) => void;
}

function Sidebar({ activeView, onNavigate }: SidebarProps) {
  const [openSections, setOpenSections] = useState<Record<string, boolean>>({
    doctors: ["doctor-grid", "doctor-details", "add-doctor", "doctor-schedule"].includes(activeView),
    patients: ["patient-grid", "patient-details", "create-patient"].includes(activeView),
    appointments: ["appointments", "new-appointment", "calendar"].includes(activeView),
    leaves: ["leaves", "leave-type"].includes(activeView),
  });

  const toggle = (key: string) =>
    setOpenSections((prev) => ({ ...prev, [key]: !prev[key] }));

  const isActive = (view: ViewId) => activeView === view;

  function NavItem({
    icon,
    label,
    view,
    expandable,
    sectionKey,
    isOpen,
  }: {
    icon: React.ReactNode;
    label: string;
    view?: ViewId;
    expandable?: boolean;
    sectionKey?: string;
    isOpen?: boolean;
  }) {
    const active = view ? isActive(view) : false;
    const expanded = expandable && sectionKey ? openSections[sectionKey] : false;

    return (
      <div
        className="relative rounded-[6px] w-full cursor-pointer select-none"
        style={{
          background: active
            ? "white"
            : "transparent",
          boxShadow: active ? "0px 1px 0.5px rgba(0,0,0,0.05)" : "none",
          border: active ? "1px solid #e7e8eb" : "1px solid transparent",
        }}
        onClick={() => {
          if (expandable && sectionKey) toggle(sectionKey);
          if (view) onNavigate(view);
        }}
      >
        <div className="flex items-center gap-[8px] px-[12px] py-[8px]">
          <div
            className="shrink-0"
            style={{ color: active ? "#2E37A4" : "#0A1B39" }}
          >
            {icon}
          </div>
          <span
            style={{
              fontFamily: "'Inter', sans-serif",
              fontWeight: 500,
              fontSize: 14,
              color: active ? "#2E37A4" : "#0A1B39",
              flex: 1,
              whiteSpace: "nowrap",
            }}
          >
            {label}
          </span>
          {expandable && (
            <span style={{ color: active ? "#2E37A4" : "#0A1B39" }}>
              {expanded ? <ChevronDown color={active ? "#2E37A4" : "#0A1B39"} /> : <ChevronRight color={active ? "#2E37A4" : "#0A1B39"} />}
            </span>
          )}
          {!expandable && view && (
            <ChevronRight color={active ? "#2E37A4" : "#0A1B39"} />
          )}
        </div>
      </div>
    );
  }

  function SubItem({
    label,
    view,
    last,
  }: {
    label: string;
    view: ViewId;
    last?: boolean;
  }) {
    const active = isActive(view);
    return (
      <div
        className="flex items-start cursor-pointer select-none w-full"
        style={{ height: 32 }}
        onClick={() => onNavigate(view)}
      >
        <TreeBullet active={active} />
        <div className="flex items-center h-full pl-[8px]">
          <span
            style={{
              fontFamily: "'Inter', sans-serif",
              fontWeight: 400,
              fontSize: 14,
              color: active ? "#2E37A4" : "#6c7688",
              whiteSpace: "nowrap",
              lineHeight: "21px",
            }}
          >
            {label}
          </span>
        </div>
      </div>
    );
  }

  function SectionLabel({ label }: { label: string }) {
    return (
      <div className="flex items-center justify-center w-full py-[0px] pt-[4px]">
        <span
          style={{
            fontFamily: "'Inter', sans-serif",
            fontWeight: 500,
            fontSize: 13,
            color: "#858d9c",
            letterSpacing: 0,
          }}
        >
          {label}
        </span>
      </div>
    );
  }

  function PlainNavItem({
    icon,
    label,
    view,
  }: {
    icon: React.ReactNode;
    label: string;
    view: ViewId;
  }) {
    const active = isActive(view);
    return (
      <div
        className="relative rounded-[6px] w-full cursor-pointer select-none"
        style={{
          background: active ? "white" : "transparent",
          boxShadow: active ? "0px 1px 0.5px rgba(0,0,0,0.05)" : "none",
          border: active ? "1px solid #e7e8eb" : "1px solid transparent",
        }}
        onClick={() => onNavigate(view)}
      >
        <div className="flex items-center gap-[8px] px-[12px] py-[8px]">
          <div className="shrink-0">{icon}</div>
          <span
            style={{
              fontFamily: "'Inter', sans-serif",
              fontWeight: 500,
              fontSize: 14,
              color: active ? "#2E37A4" : "#0A1B39",
              flex: 1,
              whiteSpace: "nowrap",
            }}
          >
            {label}
          </span>
        </div>
      </div>
    );
  }

  const doctorActive = ["doctor-grid", "doctor-details", "add-doctor", "doctor-schedule"].includes(activeView);
  const patientActive = ["patient-grid", "patient-details", "create-patient"].includes(activeView);
  const appointmentActive = ["appointments", "new-appointment", "calendar"].includes(activeView);

  return (
    <div
      className="flex flex-col bg-white h-full overflow-y-auto overflow-x-hidden"
      style={{
        width: 276,
        borderRight: "1px solid #e7e8eb",
        flexShrink: 0,
      }}
    >
      {/* Logo area */}
      <div
        className="flex items-center justify-between px-[24px] py-[16px] shrink-0"
        style={{ borderBottom: "1px solid #e7e8eb" }}
      >
        <PreclinicLogo />
        <div className="cursor-pointer opacity-60 hover:opacity-100 transition-opacity">
          <CollapseIcon />
        </div>
      </div>

      {/* Clinic info */}
      <div
        className="flex items-center gap-[10px] px-[16px] py-[12px] shrink-0"
        style={{ borderBottom: "1px solid #e7e8eb" }}
      >
        <img
          src={imgAvatarCircle}
          alt="Clinic"
          className="rounded-full shrink-0"
          style={{ width: 36, height: 36, objectFit: "cover" }}
        />
        <div className="flex flex-col min-w-0">
          <span
            style={{
              fontFamily: "'Inter', sans-serif",
              fontWeight: 600,
              fontSize: 13,
              color: "#0a1b39",
              whiteSpace: "nowrap",
            }}
          >
            Trustcare Clinic
          </span>
          <span
            style={{
              fontFamily: "'Inter', sans-serif",
              fontWeight: 400,
              fontSize: 12,
              color: "#6c7688",
            }}
          >
            Example
          </span>
        </div>
        <div className="ml-auto cursor-pointer">
          <ChevronDown color="#6c7688" />
        </div>
      </div>

      {/* Nav content */}
      <div className="flex flex-col gap-[4px] px-[12px] py-[12px] flex-1">
        {/* Main Menu */}
        <SectionLabel label="Main Menu" />
        <div className="flex flex-col gap-[4px]">
          <NavItem icon={<IconDashboard />} label="Dashboard" view="dashboard" />
          <NavItem icon={<IconApps />} label="Applications" view="applications" expandable />
          <PlainNavItem icon={<IconGlobe />} label="Front End" view="frontend" />
          <NavItem icon={<IconSidebar />} label="Layouts" view="layouts" expandable />
        </div>

        {/* Clinic */}
        <div className="mt-[8px]">
          <SectionLabel label="Clinic" />
        </div>
        <div className="flex flex-col gap-[4px]">
          {/* Doctors expandable */}
          <NavItem
            icon={<IconUserPlus />}
            label="Doctors"
            expandable
            sectionKey="doctors"
            isOpen={openSections.doctors}
            view={undefined}
          />
          <CollapsibleSection open={openSections.doctors}>
            <div className="flex flex-col pl-[16px] py-[8px] gap-0">
              <SubItem label="Doctors" view="doctor-grid" />
              <SubItem label="Doctor Details" view="doctor-details" />
              <SubItem label="Add Doctor" view="add-doctor" />
              <SubItem label="Doctor Schedule" view="doctor-schedule" last />
            </div>
          </CollapsibleSection>

          {/* Patients expandable */}
          <NavItem
            icon={<IconUserHeart />}
            label="Patients"
            expandable
            sectionKey="patients"
            isOpen={openSections.patients}
            view={undefined}
          />
          <CollapsibleSection open={openSections.patients}>
            <div className="flex flex-col pl-[16px] py-[8px] gap-0">
              <SubItem label="Patients" view="patient-grid" />
              <SubItem label="Patient Details" view="patient-details" />
              <SubItem label="Create Patient" view="create-patient" last />
            </div>
          </CollapsibleSection>

          {/* Appointments expandable */}
          <div
            className="relative rounded-[6px] w-full cursor-pointer select-none"
            style={{
              background: appointmentActive ? "white" : "transparent",
              boxShadow: appointmentActive ? "0px 1px 0.5px rgba(0,0,0,0.05)" : "none",
              border: appointmentActive ? "1px solid #e7e8eb" : "1px solid transparent",
            }}
            onClick={() => toggle("appointments")}
          >
            <div className="flex items-center gap-[8px] px-[12px] py-[8px]">
              <IconCalendar color={appointmentActive ? "#2E37A4" : "#0A1B39"} />
              <span
                style={{
                  fontFamily: "'Inter', sans-serif",
                  fontWeight: 500,
                  fontSize: 14,
                  color: appointmentActive ? "#2E37A4" : "#0A1B39",
                  flex: 1,
                }}
              >
                Appointments
              </span>
              <ChevronDown color={appointmentActive ? "#2E37A4" : "#0A1B39"} />
            </div>
          </div>
          <CollapsibleSection open={openSections.appointments}>
            <div className="flex flex-col pl-[16px] py-[8px] gap-0">
              <SubItem label="Appointments" view="appointments" />
              <SubItem label="New Appointment" view="new-appointment" />
              <SubItem label="Calendar" view="calendar" last />
            </div>
          </CollapsibleSection>

          <PlainNavItem icon={<IconMapPin />} label="Locations" view="locations" />
          <PlainNavItem icon={<IconServices />} label="Services" view="services" />
          <PlainNavItem icon={<IconStethoscope />} label="Specializations" view="specializations" />
          <PlainNavItem icon={<IconAsset />} label="Assets" view="assets" />
          <PlainNavItem icon={<IconActivity />} label="Activities" view="activities" />
          <PlainNavItem icon={<IconMessages />} label="Messages" view="messages" />
        </div>

        {/* HRM */}
        <div className="mt-[8px]">
          <SectionLabel label="HRM" />
        </div>
        <div className="flex flex-col gap-[4px]">
          <PlainNavItem icon={<IconUsers />} label="Staffs" view="staffs" />
          <PlainNavItem icon={<IconBuilding />} label="Departments" view="departments" />
          <PlainNavItem icon={<IconDesignation />} label="Designations" view="designations" />
          <PlainNavItem icon={<IconAttendance />} label="Attendance" view="attendance" />

          {/* Leaves expandable */}
          <div
            className="relative rounded-[6px] w-full cursor-pointer select-none"
            style={{ border: "1px solid transparent" }}
            onClick={() => toggle("leaves")}
          >
            <div className="flex items-center gap-[8px] px-[12px] py-[8px]">
              <IconLeaves />
              <span
                style={{
                  fontFamily: "'Inter', sans-serif",
                  fontWeight: 500,
                  fontSize: 14,
                  color: "#0A1B39",
                  flex: 1,
                }}
              >
                Leaves
              </span>
              <ChevronRight />
            </div>
          </div>
          <CollapsibleSection open={openSections.leaves}>
            <div className="flex flex-col pl-[16px] py-[8px] gap-0">
              <SubItem label="Leaves" view="leaves" />
              <SubItem label="Leave Type" view="leave-type" last />
            </div>
          </CollapsibleSection>

          <PlainNavItem icon={<IconHolidays />} label="Holidays" view="holidays" />
          <PlainNavItem icon={<IconPayroll />} label="Payroll" view="payroll" />
        </div>

        {/* Finance & Accounts */}
        <div className="mt-[8px]">
          <SectionLabel label="Finance & Accounts" />
        </div>
        <div className="flex flex-col gap-[4px] pb-[16px]">
          <PlainNavItem icon={<IconExpenses />} label="Expenses" view="expenses" />
          <PlainNavItem icon={<IconIncome />} label="Income" view="income" />
          <PlainNavItem icon={<IconInvoices />} label="Invoices" view="invoices" />
          <PlainNavItem icon={<IconPayments />} label="Payments" view="payments" />
          <PlainNavItem icon={<IconTransactions />} label="Transactions" view="transactions" />
        </div>
      </div>
    </div>
  );
}

function PlaceholderView({ title }: { title: string }) {
  return (
    <div
      className="flex flex-col items-center justify-center"
      style={{ minHeight: 600, background: "#f5f6f8" }}
    >
      <div
        style={{
          background: "white",
          border: "1px solid #e7e8eb",
          borderRadius: 12,
          padding: "48px 64px",
          textAlign: "center",
          boxShadow: "0 1px 4px rgba(0,0,0,0.06)",
        }}
      >
        <div
          style={{
            fontFamily: "'Inter', sans-serif",
            fontWeight: 700,
            fontSize: 22,
            color: "#0a1b39",
            marginBottom: 8,
          }}
        >
          {title}
        </div>
        <div
          style={{
            fontFamily: "'Inter', sans-serif",
            fontSize: 14,
            color: "#6c7688",
          }}
        >
          This section is coming soon.
        </div>
      </div>
    </div>
  );
}

function ViewContainer({
  children,
  height,
}: {
  children: React.ReactNode;
  height: number;
}) {
  return (
    <div
      style={{
        width: 1164,
        minHeight: height,
        position: "relative",
        flex: "0 0 auto",
      }}
    >
      {children}
    </div>
  );
}

// Render the imported component's content (without its built-in sidebar).
// We do this by rendering the full imported component in a container
// that only shows the right portion (left-276px offset).
function ImportedView({
  component: Component,
  height,
}: {
  component: React.ComponentType;
  height: number;
}) {
  return (
    // This outer div clips the left 276px sidebar from the import
    <div
      style={{
        width: 1164,
        height,
        overflow: "hidden",
        position: "relative",
        flexShrink: 0,
      }}
    >
      {/* Render the full imported component (1440px wide) shifted left by 276px */}
      <div
        style={{
          width: 1440,
          height,
          position: "absolute",
          left: -276,
          top: 0,
        }}
      >
        <Component />
      </div>
    </div>
  );
}

export default function App() {
  const [activeView, setActiveView] = useState<ViewId>("appointments");

  const renderView = () => {
    switch (activeView) {
      case "doctor-details":
        return (
          <ImportedView component={DoctorDetails} height={getViewHeight("doctor-details")} />
        );
      case "add-doctor":
        return (
          <ImportedView component={AddNewDoctor} height={getViewHeight("add-doctor")} />
        );
      case "doctor-grid":
        return (
          <ImportedView component={DoctorGrid} height={getViewHeight("doctor-grid")} />
        );
      case "create-patient":
        return (
          <ImportedView component={AddPatient} height={getViewHeight("create-patient")} />
        );
      case "patient-details":
        return (
          <ImportedView component={PatientDetails} height={getViewHeight("patient-details")} />
        );
      case "patient-grid":
        return (
          <ImportedView component={PatientGrid} height={getViewHeight("patient-grid")} />
        );
      case "appointments":
        return (
          <ImportedView component={Appointment} height={getViewHeight("appointments")} />
        );
      default:
        return (
          <ViewContainer height={500}>
            <PlaceholderView
              title={activeView
                .split("-")
                .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
                .join(" ")}
            />
          </ViewContainer>
        );
    }
  };

  return (
    <div
      className="flex"
      style={{
        width: "100vw",
        height: "100vh",
        overflow: "hidden",
        background: "#f5f6f8",
      }}
    >
      {/* Sidebar */}
      <Sidebar activeView={activeView} onNavigate={setActiveView} />

      {/* Main content area */}
      <div
        style={{
          flex: 1,
          overflowX: "auto",
          overflowY: "auto",
          minWidth: 0,
        }}
      >
        {renderView()}
      </div>
    </div>
  );
}
